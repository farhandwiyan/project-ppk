<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Fasilitas;
use App\Models\Reservation;
use App\Models\User;
use App\Models\LaporanKerusakan;
use App\Services\ReservationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    public function __construct(private ReservationService $service) {}

    public function showCreateForm(Request $request, Fasilitas $fasilitas) {
        $tanggalInput = $request->query('tanggal');
    
        $tanggalTerpilih = $tanggalInput 
            ? Carbon::parse($tanggalInput) 
            : now()->addDays(7);
            
        return view('reservations.create', [
            'fasilitas'       => $fasilitas,
            'tanggalTerpilih' => $tanggalTerpilih,
        ]);
    }

    public function create(StoreReservationRequest $request) {
        $reservation = $this->service->ajukan(
            $request->validated(),
            Auth::user()->id,
            $request->file('surat_peminjaman'),
            $request->file('proposal_kegiatan')
        );

        return redirect()->route('reservations.riwayat', $reservation)->with('success', 'Reservasi berhasil diajukan, menunggu persetujuan petugas');
    }

    public function riwayat(Request $request) {
        $userId = Auth::id();
 
        $search      = $request->input('search_peminjaman');
        $filterJenis = $request->input('filter_jenis_peminjaman');
        $filterStatus = $request->input('filter_status_peminjaman');
        $tanggal     = $request->input('tanggal_peminjaman');
 
        $reservations = Reservation::select(
                'id', 'fasilitas_id', 'tanggal', 'start_time', 'end_time',
                'deskripsi_kegiatan', 'status'
            )
            ->with('fasilitas:id,nama,tipe_fasilitas')
            ->where('user_id', $userId)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('deskripsi_kegiatan', 'like', "%{$search}%")
                      ->orWhere('nama_kegiatan', 'like', "%{$search}%")
                      ->orWhereHas('fasilitas', function ($fq) use ($search) {
                          $fq->where('nama', 'like', "%{$search}%");
                      });
                });
            })
            ->when($filterJenis, function ($query, $filterJenis) {
                $query->whereHas('fasilitas', function ($fq) use ($filterJenis) {
                    $fq->where('tipe_fasilitas', $filterJenis);
                });
            })
            ->when($filterStatus, function ($query, $filterStatus) {
                $query->where('status', $filterStatus);
            })
            ->when($tanggal, function ($query, $tanggal) {
                $query->whereDate('tanggal', $tanggal);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();
 
        $peminjamanStats = [
            'total'      => Reservation::where('user_id', $userId)->count(),
            'berjalan'   => Reservation::where('user_id', $userId)->where('status', 'menunggu')->count(),
            'selesai'    => Reservation::where('user_id', $userId)->where('status', 'disetujui')->count(),
            'dibatalkan' => Reservation::where('user_id', $userId)->where('status', 'dibatalkan')->count(),
        ];
 
        $tipeOptions = ['Ruangan', 'Aula', 'Lapangan Olahraga', 'Laboratorium', 'Alat'];

        $laporans = LaporanKerusakan::with('fasilitas')
        ->where('user_id', auth()->id())
        ->latest()
        ->get();

        $laporanStats = [
            'total' => $laporans->count(),
            'diproses' => $laporans->where('status', 'diproses')->count(),
            'selesai' => $laporans->where('status', 'selesai')->count(),
            'ditolak' => $laporans->where('status', 'ditolak')->count(),
        ];
 
        return view('reservations.riwayat', compact(
            'reservations', 'peminjamanStats', 'tipeOptions', 'laporans', 'laporanStats'
        ));
    }

    public function showDetail($id) {
        $reservation = Reservation::findOrFail($id);

        return view('reservations.detail', compact('reservation'));
    }

    public function cancel(Request $request, Reservation $reservation) {
        abort_unless($reservation->user_id === Auth::id(), 403);

        $reservation = $this->service->batalkanOlehUser(
            $reservation, 
            $request->alasan
        );

        return redirect()->route('reservations.riwayat')->with('success', 'Reservasi berhasil dibatalkan');
    }

    public function cancelByPetugas(Request $request, $id): RedirectResponse
    {
        $validated = $request->validate([
            'laporan_id' => ['required', 'exists:laporan_kerusakan,id'],
            'alasan_pembatalan' => ['required', 'string', 'max:1000'],
            ]);

        $laporan = LaporanKerusakan::findOrFail($validated['laporan_id']);
        $reservasi = Reservation::findOrFail($id);

        if ($reservasi->fasilitas_id != $laporan->fasilitas_id) {
            return redirect()
                ->route('petugas.laporan.show', $laporan->id)
                ->with('error', 'Reservasi tidak terkait dengan fasilitas pada laporan ini.');
        }

        if (! in_array($reservasi->status, ['menunggu', 'disetujui'], true)) {
            return redirect()
                ->route('petugas.laporan.show', $laporan->id);
        }

        $reservasi->update([
            'status' => 'dibatalkan',
            'dibatalkan_oleh' => 'petugas',
            'alasan_pembatalan' => $validated['alasan_pembatalan'],
            'diproses_oleh' => Auth::user()->id,
            'diproses_pada' => now(),
        ]);

        return redirect()
            ->route('petugas.laporan.show', $laporan->id)
            ->with('success', 'Reservasi berhasil dibatalkan.');
    }
}
