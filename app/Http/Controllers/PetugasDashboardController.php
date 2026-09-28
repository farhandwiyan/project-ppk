<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;

// use App\Models\LaporanKerusakan; // Nanti buka komentar ini jika modelnya sudah ada

class PetugasDashboardController extends Controller
{
    // Fungsi untuk menampilkan halaman Dashboard Utama
    public function index()
    {
        $reservations = Reservation::with(['user', 'fasilitas'])->get();

        $antreanReservasi = Reservation::where('status', 'menunggu')->count();
        $antreanBatal     = Reservation::where('status', 'dibatalkan')->count();
        
        $antreanKerusakan = 0; 
        $sedangDiperbaiki = 0;

        return view('petugas.dashboard', compact(
            'reservations', 'antreanReservasi', 'antreanBatal', 'antreanKerusakan', 'sedangDiperbaiki'
        ));
    }

    // FUNGSI BARU: Untuk menampilkan halaman Lihat Detail
    public function show($id)
    {
        // Mencari data reservasi berdasarkan ID, beserta relasinya
        $reservasi = Reservation::with(['user', 'fasilitas'])->findOrFail($id);
        
        // Mengarahkan ke file resources/views/petugas/detail.blade.php
        return view('petugas.detail', compact('reservasi'));
    }

    // Fungsi untuk tombol Setuju (yang sekarang ada di dalam halaman detail)
    public function setuju($id)
    {
        $reservasi = Reservation::findOrFail($id);
        $reservasi->update([
            'status' => 'disetujui',
            'diproses_oleh' => Auth::user()->id,
            'diproses_pada' => now(),
        ]);     
        
        // Setelah disetujui, kembalikan petugas ke halaman dashboard utama
        return redirect()->route('petugas.home')->with('success', 'Reservasi disetujui.');
    }

    // Fungsi untuk tombol Tolak (yang sekarang ada di dalam halaman detail)
    public function tolak($id)
    {
        $reservasi = Reservation::findOrFail($id);
        $reservasi->update([
            'status' => 'ditolak',
            'dibatalkan_oleh' => Auth::user()->id,
        ]);
        
        // Setelah ditolak, kembalikan petugas ke halaman dashboard utama
        return redirect()->route('petugas.home')->with('success', 'Reservasi ditolak.');
    }
}