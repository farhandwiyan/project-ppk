<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use App\Models\LaporanKerusakan; 

class PetugasDashboardController extends Controller
{
    // Status yang masuk ke halaman Riwayat
    private array $statusRiwayat = ['disetujui', 'ditolak', 'dibatalkan'];

    // Fungsi untuk menampilkan halaman Dashboard Utama (hanya yang menunggu)
    public function index(Request $request)
    {
        // 1. Mulai query dasar (hanya ambil yang statusnya menunggu)
        $query = Reservation::with(['user', 'fasilitas'])
            ->where('status', 'menunggu');

        // 2. Logika Filter Tipe Fasilitas
        if ($request->filled('tipe_fasilitas')) {
            $query->whereHas('fasilitas', function($q) use ($request) {
                $q->where('tipe_fasilitas', $request->tipe_fasilitas); 
            });
        }

        // 3. Logika Filter Lokasi
        if ($request->filled('lokasi')) {
            $query->whereHas('fasilitas', function($q) use ($request) {
                $q->where('lokasi', $request->lokasi); 
            });
        }

        // 4. Logika Urutan Waktu Acara (Sorting)
        if ($request->sort == 'terlama') {
            $query->oldest('tanggal')->oldest('start_time');
        } else {
            $query->latest('tanggal')->latest('start_time');
        }

        // Eksekusi query untuk mengambil datanya
        $reservations = $query->get();

        // Hitung statistik untuk kartu dashboard atas
        $antreanReservasi = Reservation::where('status', 'menunggu')->count();
        $antreanBatal     = Reservation::where('status', 'dibatalkan')->count();
        $antreanKerusakan = LaporanKerusakan::where('status', 'baru')->count();
        $sedangDiperbaiki = 0;

        // 5. Ambil daftar Tipe Fasilitas & Lokasi yang unik untuk Dropdown HTML
        $daftarTipe = \App\Models\Fasilitas::select('tipe_fasilitas')->distinct()->pluck('tipe_fasilitas');
        $daftarLokasi = \App\Models\Fasilitas::select('lokasi')->distinct()->pluck('lokasi');

        return view('petugas.dashboard', compact(
            'reservations', 'antreanReservasi', 'antreanBatal', 'antreanKerusakan', 'sedangDiperbaiki', 'daftarTipe', 'daftarLokasi'
        ));
    }

    

    // Halaman Riwayat (disetujui / ditolak / dibatalkan) + filter status
    public function riwayat(Request $request)
    {
        $query = Reservation::with(['user', 'fasilitas'])
            ->whereIn('status', $this->statusRiwayat);

        if ($request->filled('status') && in_array($request->status, $this->statusRiwayat)) {
            $query->where('status', $request->status);
        }

        return view('petugas.riwayat', [
            'reservations'    => $query->latest()->paginate(10),
            'totalDisetujui'  => Reservation::where('status', 'disetujui')->count(),
            'totalDitolak'    => Reservation::where('status', 'ditolak')->count(),
            'totalDibatalkan' => Reservation::where('status', 'dibatalkan')->count(),
        ]);
    }

    // Hapus satu riwayat (hanya yang statusnya sudah final)
    public function destroyRiwayat($id)
    {
        $reservasi = Reservation::whereIn('status', $this->statusRiwayat)->findOrFail($id);
        $reservasi->delete();

        return redirect()->route('petugas.riwayat')
            ->with('success', 'Riwayat reservasi berhasil dihapus.');
    }

    // Untuk menampilkan halaman Lihat Detail
    public function show($id)
    {
        // Mencari data reservasi berdasarkan ID, beserta relasinya
        $reservasi = Reservation::with(['user', 'fasilitas'])->findOrFail($id);

        // Mengarahkan ke file resources/views/petugas/detail.blade.php
        return view('petugas.detail', compact('reservasi'));
    }

    // Fungsi untuk tombol Setuju (dengan cek bentrok jadwal)
    public function setuju($id)
    {
        $reservasi = Reservation::with('fasilitas')->findOrFail($id);
 
        if ($reservasi->status !== 'menunggu') {
            return redirect()->route('petugas.home')
                ->with('error', 'Hanya reservasi berstatus menunggu yang bisa disetujui.');
        }
 
        $queryOverlap = Reservation::where('fasilitas_id', $reservasi->fasilitas_id)
            ->where('tanggal', $reservasi->tanggal)
            ->where('status', 'disetujui')
            ->where('id', '!=', $reservasi->id)
            ->where('start_time', '<', $reservasi->end_time)
            ->where('end_time', '>', $reservasi->start_time);
 
        if ($reservasi->fasilitas->isAlat()) {
            // tipe alat: kapasitas = jumlah unit stok, jumlah_peserta = jumlah unit dipinjam
            $terpakai = (clone $queryOverlap)->sum('jumlah_peserta');
 
            if ($terpakai + $reservasi->jumlah_peserta > $reservasi->fasilitas->kapasitas) {
                $sisa = max($reservasi->fasilitas->kapasitas - $terpakai, 0);
                return redirect()->route('petugas.reservasi.show', $reservasi->id)
                    ->with('error', "Stok alat tidak mencukupi untuk menyetujui reservasi ini. Sisa stok: {$sisa} unit.");
            }
        } else {
            if ($queryOverlap->exists()) {
                return redirect()->route('petugas.reservasi.show', $reservasi->id)
                    ->with('error', 'Jadwal bentrok dengan reservasi lain yang sudah disetujui.');
            }
        }
 
        $reservasi->update([
            'status'        => 'disetujui',
            'diproses_oleh' => Auth::id(),
            'diproses_pada' => now(),
        ]);
 
        // Setelah disetujui, kembalikan petugas ke halaman dashboard utama
        return redirect()->route('petugas.home')->with('success', 'Reservasi disetujui.');
    }
 
    // Fungsi untuk tombol Tolak
    public function tolak(Request $request, $id)
    {
        $reservasi = Reservation::findOrFail($id);
 
        if ($reservasi->status !== 'menunggu') {
            return redirect()->route('petugas.home')
                ->with('error', 'Hanya reservasi berstatus menunggu yang bisa ditolak.');
        }
 
        $reservasi->update([
            'dibatalkan_oleh' => 'petugas',
            'status'           => 'ditolak',
            'diproses_oleh'    => Auth::id(),
            'diproses_pada'    => now(),
            'alasan_pembatalan' => $request->alasan,
        ]);
 
        // Setelah ditolak, kembalikan petugas ke halaman dashboard utama
        return back()->with('success', 'Reservasi berhasil ditolak.');
    }
}