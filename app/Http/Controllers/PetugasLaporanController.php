<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PetugasLaporanController extends Controller
{
    private array $daftarStatus = [
        LaporanKerusakan::STATUS_BARU,
        LaporanKerusakan::STATUS_DIPROSES,
        LaporanKerusakan::STATUS_SELESAI,
        LaporanKerusakan::STATUS_DITOLAK,
    ];

    // Daftar laporan kerusakan yang masuk + filter status
    public function index(Request $request)
    {
        $query = LaporanKerusakan::with(['user', 'fasilitas']);

        if ($request->filled('status') && in_array($request->status, $this->daftarStatus, true)) {
            $query->where('status', $request->status);
        }

        $jumlahPerStatus = LaporanKerusakan::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('petugas.laporan.index', [
            'laporan' => $query->latest()->paginate(10)->withQueryString(),
            'daftarStatus' => $this->daftarStatus,
            'jumlahPerStatus' => $jumlahPerStatus,
        ]);
    }

    // Halaman detail laporan + form perubahan status
    public function show($id)
    {
        $laporan = LaporanKerusakan::with(['user', 'fasilitas', 'petugas'])->findOrFail($id);

        return view('petugas.laporan.detail', compact('laporan'));
    }

    // Ubah status laporan (diproses / selesai / ditolak)
    public function updateStatus(Request $request, $id): RedirectResponse
    {
        $laporan = LaporanKerusakan::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', Rule::in([
                LaporanKerusakan::STATUS_DIPROSES,
                LaporanKerusakan::STATUS_SELESAI,
                LaporanKerusakan::STATUS_DITOLAK,
            ])],
            'alasan_penolakan' => ['nullable', 'required_if:status,'.LaporanKerusakan::STATUS_DITOLAK, 'string', 'max:1000'],
            'catatan_penyelesaian' => ['nullable', 'required_if:status,'.LaporanKerusakan::STATUS_SELESAI, 'string', 'max:1000'],
        ], [
            'status.required' => 'Status laporan wajib dipilih.',
            'status.in' => 'Status laporan tidak valid.',
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi.',
            'catatan_penyelesaian.required_if' => 'Catatan penyelesaian wajib diisi.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 1000 karakter.',
            'catatan_penyelesaian.max' => 'Catatan penyelesaian maksimal 1000 karakter.',
        ]);

        if (! $laporan->bisaDiubahKe($validated['status'])) {
            return redirect()->route('petugas.laporan.show', $laporan->id)
                ->with('error', "Laporan berstatus {$laporan->status} tidak dapat diubah menjadi {$validated['status']}.");
        }

        $perubahan = [
            'status' => $validated['status'],
            'diproses_oleh' => Auth::id(),
            'diproses_pada' => now(),
        ];

        if ($validated['status'] === LaporanKerusakan::STATUS_SELESAI) {
            $perubahan['catatan_penyelesaian'] = $validated['catatan_penyelesaian'];
        }

        if ($validated['status'] === LaporanKerusakan::STATUS_DITOLAK) {
            $perubahan['alasan_penolakan'] = $validated['alasan_penolakan'];
        }

        $laporan->update($perubahan);

        return redirect()->route('petugas.laporan.show', $laporan->id)
            ->with('success', "Status laporan berhasil diubah menjadi {$validated['status']}.");
    }
}