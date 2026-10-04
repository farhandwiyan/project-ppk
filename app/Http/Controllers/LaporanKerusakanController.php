<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\LaporanKerusakan;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LaporanKerusakanController extends Controller
{
    public function create()
    {
        $fasilitas = Fasilitas::where('status', 'aktif')->get();

        return view('laporan.create', compact('fasilitas'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'lokasi' => 'required|string',
            'jenis' => 'required|string',
<<<<<<< Updated upstream
            'fasilitas_id' => ['required', Rule::exists('fasilitas', 'id')->where(function ($query) use ($request) {
                $query->where('lokasi', $request->lokasi)
                ->where('tipe_fasilitas', $request->jenis);
=======
            'fasilitas_id' => [
                'required', 
                Rule::exists('fasilitas', 'id')->where(function ($query) use ($request) {
                    $query->where('lokasi', $request->lokasi)
                          ->where('tipe_fasilitas', $request->jenis); // <-- UBAH KATA 'jenis' MENJADI 'tipe_fasilitas' DI SINI
>>>>>>> Stashed changes
                }),
            ],
            'nama_pelapor' => 'required|string|max:25',
            'email' => 'required|email|max:50',
            'nomor_telepon' => 'required|string|max:13',
            'deskripsi' => 'required|string',
            'bukti_kerusakan' => 'required|array|min:1|max:5',
            'bukti_kerusakan.*' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $paths = [];

        if ($request->hasFile('bukti_kerusakan')) {
            foreach ($request->file('bukti_kerusakan') as $file) {
                $paths[] = $file->store('laporan', 'public');
            }
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'diproses';
        $validated['bukti_kerusakan'] = $paths;

        LaporanKerusakan::create($validated);

        return redirect()
        ->route('laporan.create')
        ->with('success', 'Laporan kerusakan berhasil dikirim.');
    }

    public function show(LaporanKerusakan $laporan)
    {
        if ($laporan->user_id !== auth()->id()) {
            abort(403);
        }

        $laporan->load('fasilitas');

        return view('laporan.detail', compact('laporan'));
    }
}