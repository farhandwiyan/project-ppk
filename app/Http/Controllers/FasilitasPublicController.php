<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FasilitasPublicController extends Controller
{
    public function index(Request $request)
    {
        $search      = $request->input('search');
        $filterLokasi = $request->input('filter_lokasi');
        $filterTipe   = $request->input('filter_tipe');

        // Default tanggal: H+1 dari hari ini, kecuali user sudah memilih tanggal lain
        $tanggalTerpilih = $request->filled('tanggal')
            ? Carbon::parse($request->input('tanggal'))
            : Carbon::tomorrow();

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
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $lokasiOptions = Fasilitas::select('lokasi')->distinct()->pluck('lokasi');
        $tipeOptions   = Fasilitas::select('tipe_fasilitas')->distinct()->pluck('tipe_fasilitas');

        return view('public.fasilitas-public', compact(
            'fasilitas', 'lokasiOptions', 'tipeOptions', 'tanggalTerpilih'
        ));
    }
}