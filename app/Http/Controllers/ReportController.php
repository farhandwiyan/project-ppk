<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fasilitas;
use App\Models\Reservation;
use App\Models\LaporanKerusakan;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    /**
     * Tampilkan Halaman Rekap Okupansi
     */
    public function okupansi(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $lokasiFilter = $request->input('lokasi');

        $query = Fasilitas::query();
        if ($lokasiFilter) {
            $query->where('lokasi', $lokasiFilter);
        }

        $fasilitas = $query->with(['reservations' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal', [$startDate, $endDate])
              ->where('status', 'disetujui'); 
        }])->get()->map(function($item) use ($startDate, $endDate) {
            $totalMinutes = 0;
            foreach($item->reservations as $res) {
                $start = Carbon::parse($res->start_time);
                $end = Carbon::parse($res->end_time);
                $totalMinutes += $start->diffInMinutes($end);
            }
            $totalHours = $totalMinutes / 60;
            $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
            $maxAvailableHours = $days * 13; 
            
            $item->total_reservations = $item->reservations->count();
            $item->total_hours = round($totalHours, 1);
            $item->occupancy = $maxAvailableHours > 0 ? round(($totalHours / $maxAvailableHours) * 100, 2) : 0;
            return $item;
        });

        $lokasiList = Fasilitas::select('lokasi')->distinct()->pluck('lokasi');

        return view('admin.laporan.okupansi', compact('fasilitas', 'startDate', 'endDate', 'lokasiFilter', 'lokasiList'));
    }

    /**
     * Tampilkan Halaman Rekap Kerusakan
     */
    public function kerusakan(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $lokasiFilter = $request->input('lokasi');

        $query = Fasilitas::query();
        if ($lokasiFilter) {
            $query->where('lokasi', $lokasiFilter);
        }

        $fasilitas = $query->withCount(['laporanKerusakan as total_laporan' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }])->orderByDesc('total_laporan')->get();

        $lokasiList = Fasilitas::select('lokasi')->distinct()->pluck('lokasi');

        return view('admin.laporan.kerusakan', compact('fasilitas', 'startDate', 'endDate', 'lokasiFilter', 'lokasiList'));
    }

    /**
     * Proses Export Okupansi (PDF & CSV)
     */
    public function exportOkupansi(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $lokasiFilter = $request->input('lokasi');

        $query = Fasilitas::query();
        if ($lokasiFilter) {
            $query->where('lokasi', $lokasiFilter);
        }

        $fasilitas = $query->with(['reservations' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('tanggal', [$startDate, $endDate])
              ->where('status', 'disetujui'); 
        }])->get()->map(function($item) use ($startDate, $endDate) {
            $totalMinutes = 0;
            foreach($item->reservations as $res) {
                $start = Carbon::parse($res->start_time);
                $end = Carbon::parse($res->end_time);
                $totalMinutes += $start->diffInMinutes($end);
            }
            $totalHours = $totalMinutes / 60;
            $days = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1;
            $maxAvailableHours = $days * 13; 
            
            $item->total_reservations = $item->reservations->count();
            $item->total_hours = round($totalHours, 1);
            $item->occupancy = $maxAvailableHours > 0 ? round(($totalHours / $maxAvailableHours) * 100, 2) : 0;
            return $item;
        });

        $type = $request->input('type');

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.cetak_okupansi', compact('fasilitas', 'startDate', 'endDate'));
            return $pdf->download('Rekap_Okupansi_KARSA.pdf');
        }

        // 3. Eksekusi Excel (Trik HTML to XLS)
        if ($type === 'excel') {
            $fileName = "Rekap_Okupansi_KARSA.xls";
            
            $headers = [
                "Content-type"        => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            // Kita langsung "membuang" view HTML cetak_okupansi agar di-download sebagai file Excel
            return response()->view('admin.laporan.cetak_okupansi', compact('fasilitas', 'startDate', 'endDate'), 200, $headers);
        }
    }

    /**
     * Proses Export Kerusakan (PDF & Excel)
     */
    public function exportKerusakan(Request $request) 
    {
        // 1. Ambil data dengan logika persis seperti di halaman view kerusakan
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());
        $lokasiFilter = $request->input('lokasi');

        $query = Fasilitas::query();
        if ($lokasiFilter) {
            $query->where('lokasi', $lokasiFilter);
        }

        $fasilitas = $query->withCount(['laporanKerusakan as total_laporan' => function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }])->orderByDesc('total_laporan')->get();

        $type = $request->input('type');

        // 2. Eksekusi PDF
        if ($type === 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.cetak_kerusakan', compact('fasilitas', 'startDate', 'endDate'));
            return $pdf->download('Rekap_Kerusakan_KARSA.pdf');
        }

        // 3. Eksekusi Excel (Trik HTML to XLS)
        if ($type === 'excel') {
            $fileName = "Rekap_Kerusakan_KARSA.xls";
            
            $headers = [
                "Content-type"        => "application/vnd.ms-excel",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ];

            return response()->view('admin.laporan.cetak_kerusakan', compact('fasilitas', 'startDate', 'endDate'), 200, $headers);
        }
    }
}