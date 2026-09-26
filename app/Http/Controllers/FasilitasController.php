<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FasilitasController extends Controller
{
    public function getAllFasilitas(Request $request) {
        $search = $request->input('search');
        $filterTipe = $request->input('filter_tipe');
        $filterLokasi = $request->input('filter_lokasi');

        $fasilitas = Fasilitas::select('id', 'nama', 'tipe_fasilitas', 'lokasi', 'kapasitas')
        ->when($search, function ($query, $search) {
            $query->where('nama', 'like', '%' . $search . '%');
        })
        ->when($filterTipe, function ($query, $filterTipe) {
            $query->where('tipe_fasilitas', $filterTipe);
        })
        ->when($filterLokasi, function ($query, $filterLokasi) {
            $query->where('lokasi', $filterLokasi);
        })->latest()->paginate(20)->withQueryString();

        $tipeOptions = Fasilitas::select('tipe_fasilitas')->distinct()->pluck('tipe_fasilitas');
        $lokasiOptions = Fasilitas::select('lokasi')->distinct()->pluck('lokasi');


        return view('admin.fasilitas', compact(
            'fasilitas', 'search', 'filterTipe', 'filterLokasi', 'tipeOptions', 'lokasiOptions'
        ));
    }

    public function showCreateForm()
    {
        return view('fasilitas.create');
    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:100',
            'tipe_fasilitas'  => 'required|string|max:50',
            'lokasi'          => 'required|string|max:50',
            'deskripsi'       => 'required|string',
            'kapasitas'       => 'required|integer|min:1',
        ]);

        $duplikat = Fasilitas::where('nama', $validated['nama'])
            ->where('tipe_fasilitas', $validated['tipe_fasilitas'])
            ->where('lokasi', $validated['lokasi'])
            ->exists();

        if ($duplikat) {
            return back()->withInput()->withErrors([
                'nama' => 'Fasilitas dengan nama, tipe, dan lokasi yang sama sudah terdaftar.',
            ]);
        }

        $validated['created_by'] = Auth::user()->id;

        Fasilitas::create($validated);

        return redirect()->route('fasilitas.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function showEditForm(Fasilitas $fasilitas)
    {
        return view('fasilitas.edit', compact('fasilitas'));
    }

    public function update(Request $request, Fasilitas $fasilitas)
    {
        $validated = $request->validate([
            'nama'            => 'required|string|max:25',
            'tipe_fasilitas'  => 'required|string|max:50',
            'lokasi'          => 'required|string|max:50',
            'deskripsi'       => 'required|string',
            'kapasitas'       => 'required|integer|min:1',
        ]);

        $duplikat = Fasilitas::where('nama', $validated['nama'])
            ->where('tipe_fasilitas', $validated['tipe_fasilitas'])
            ->where('lokasi', $validated['lokasi'])
            ->where('id', '!=', $fasilitas->id)
            ->exists();

        if ($duplikat) {
            return back()
                ->withInput()
                ->withErrors([
                    'nama' => 'Fasilitas dengan nama, tipe, dan lokasi yang sama sudah terdaftar.',
                ]);
        }

        $fasilitas->update($validated);

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function delete(Fasilitas $fasilitas)
    {
        $fasilitas->delete();

        return redirect()
            ->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}
