<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

// use App\Models\LaporanKerusakan; // Nanti buka komentar ini jika modelnya sudah ada

class PetugasDashboardController extends Controller
{
    // Status yang masuk ke halaman Riwayat
    private array $statusRiwayat = ['disetujui', 'ditolak', 'dibatalkan'];

    // Fungsi untuk menampilkan halaman Dashboard Utama (hanya yang menunggu)
    public function index()
    {
        $reservations = Reservation::with(['user', 'fasilitas'])
            ->where('status', 'menunggu')
            ->latest()
            ->get();

        $antreanReservasi = Reservation::where('status', 'menunggu')->count();
        $antreanBatal     = Reservation::where('status', 'dibatalkan')->count();

        $antreanKerusakan = 0;
        $sedangDiperbaiki = 0;

        return view('petugas.dashboard', compact(
            'reservations', 'antreanReservasi', 'antreanBatal', 'antreanKerusakan', 'sedangDiperbaiki'
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
        $reservasi = Reservation::findOrFail($id);

        if ($reservasi->status !== 'menunggu') {
            return redirect()->route('petugas.home')
                ->with('error', 'Hanya reservasi berstatus menunggu yang bisa disetujui.');
        }

        // Cek bentrok: fasilitas & tanggal sama, jam tumpang tindih, sudah disetujui
        $bentrok = Reservation::where('fasilitas_id', $reservasi->fasilitas_id)
            ->where('tanggal', $reservasi->tanggal)
            ->where('status', 'disetujui')
            ->where('id', '!=', $reservasi->id)
            ->where('start_time', '<', $reservasi->end_time)
            ->where('end_time', '>', $reservasi->start_time)
            ->exists();

        if ($bentrok) {
            return redirect()->route('petugas.reservasi.show', $reservasi->id)
                ->with('error', 'Jadwal bentrok dengan reservasi lain yang sudah disetujui.');
        }

        $reservasi->update([
            'status'        => 'disetujui',
            'diproses_oleh' => Auth::user()->id,
            'diproses_pada' => now(),
        ]);

        // Setelah disetujui, kembalikan petugas ke halaman dashboard utama
        return redirect()->route('petugas.home')->with('success', 'Reservasi disetujui.');
    }

    // Fungsi untuk tombol Tolak
    public function tolak($id)
    {
        $reservasi = Reservation::findOrFail($id);

        if ($reservasi->status !== 'menunggu') {
            return redirect()->route('petugas.home')
                ->with('error', 'Hanya reservasi berstatus menunggu yang bisa ditolak.');
        }

        $reservasi->update([
            'status'          => 'ditolak',
            'dibatalkan_oleh' => Auth::user()->id,
        ]);

        // Setelah ditolak, kembalikan petugas ke halaman dashboard utama
        return redirect()->route('petugas.home')->with('success', 'Reservasi ditolak.');
    }
}