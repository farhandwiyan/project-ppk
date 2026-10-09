<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Reservation; // <-- Tambahkan ini untuk memanggil data reservasi
use Carbon\Carbon;
use Illuminate\Http\Request;

class FasilitasPublicController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->input('search');
        $filterLokasi = $request->input('filter_lokasi');
        $filterTipe   = $request->input('filter_tipe');
        $filterKapasitas = $request->input('filter_kapasitas');

        // Default tanggal: H+7 dari hari ini, kecuali user sudah memilih tanggal lain
        $tanggalTerpilih = $request->filled('tanggal')
            ? Carbon::parse($request->input('tanggal'))
            : Carbon::today()->addDays(7);

        $fasilitas = Fasilitas::query()
            ->when($search, function ($query, $search) {
                $query->where('nama', 'like', '%' . $search . '%');
            })
            ->when($filterLokasi, function ($query, $filterLokasi) {
                $query->where('lokasi', $filterLokasi);
            })
            ->when($filterTipe, function ($query, $filterTipe) {
                $query->where('tipe_fasilitas', $filterTipe);

            })
            // <-- Logika filter kapasitas (berdasarkan rentang jumlah orang) -->
            ->when($filterKapasitas, function ($query, $filterKapasitas) {
                if ($filterKapasitas == 'kecil') {
                    $query->where('kapasitas', '<', 50);
                } elseif ($filterKapasitas == 'sedang') {
                    $query->where('kapasitas', '>=', 50)->where('kapasitas', '<=', 200);
                } elseif ($filterKapasitas == 'besar') {
                    $query->where('kapasitas', '>', 200);
                }
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $lokasiOptions = Fasilitas::select('lokasi')->distinct()->pluck('lokasi');
        $tipeOptions   = Fasilitas::select('tipe_fasilitas')->distinct()->pluck('tipe_fasilitas');

        return view('public.fasilitas-public', compact(
            'fasilitas', 'lokasiOptions', 'tipeOptions', 
        ));
    }

    // <-- mengambil data reservasi -->
    public function show(Fasilitas $fasilitas, Request $request)
    {
        // 1. Ambil tanggal dari input (jika diubah di kalender view), default hari ini
        $tanggalPilih = $request->input('tanggal', Carbon::today()->toDateString());

        // 2. Format tanggal untuk ditampilkan lebih rapi di desain
        $tanggalFormat = Carbon::parse($tanggalPilih)->locale('id')->isoFormat('dddd, DD MMMM YYYY');

        // 3. Tarik data peminjaman yang berstatus menunggu/disetujui pada fasilitas & tanggal tersebut
        $reservasiHariIni = Reservation::where('fasilitas_id', $fasilitas->id)
            ->whereDate('tanggal', $tanggalPilih)
            ->whereIn('status', ['menunggu', 'disetujui']) 
            ->get();

        return view('public.fasilitas-detail', compact(
            'fasilitas', 
            'reservasiHariIni', 
            'tanggalPilih', 
            'tanggalFormat'
        ));
    }
}