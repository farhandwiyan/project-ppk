<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Reservasi - KARSA</title>
    <link rel="stylesheet" href="{{ asset('css/reservations/detail.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/sweetalert-helpers.js') }}"></script>
</head>
<body>
    <main class="page-content">

        @if (session('success'))
            <div class="alert-error" style="background-color:#e6f4ea;color:#1e7e34;border-color:#c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        {{-- tampilkan pesan error (mis. dari PembatalanTidakDiizinkanException kalau lolos dari UI tapi ditolak server) --}}
        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="btn-kembali">
            <a href="{{ route('reservations.riwayat') }}" class="btn-secondary">&laquo; Kembali</a>
        </div>
        
        <section class="reservasi-card">

            <div class="reservasi-header">
                <h2>{{ $reservation->nama_kegiatan }}</h2>
                <p>Status:
                    <strong>
                        @switch($reservation->status)
                            @case('menunggu') Menunggu Persetujuan @break
                            @case('disetujui') Disetujui @break
                            @case('ditolak') Ditolak @break
                            @case('dibatalkan') Dibatalkan @break
                        @endswitch
                    </strong>
                </p>
            </div>

            <div class="reservasi-form">
                <p><strong>Fasilitas:</strong> {{ $reservation->fasilitas->nama }}</p>
                <p><strong>Pemohon:</strong> {{ $reservation->nama_pemohon }}</p>
                <p><strong>Instansi:</strong> {{ $reservation->instansi_pemohon }}</p>
                <p><strong>Tanggal:</strong> {{ $reservation->tanggal->format('d M Y') }}</p>
                <p><strong>Waktu:</strong> {{ $reservation->start_time }} - {{ $reservation->end_time }} WIB</p>
                <p><strong>Deskripsi:</strong> {{ $reservation->deskripsi_kegiatan }}</p>
                <p><strong>{{ $reservation->fasilitas->isAlat() ? 'Jumlah Unit' : 'Estimasi Peserta' }}:</strong> {{ $reservation->jumlah_peserta }}</p>

                <p>
                    <strong>Surat Peminjaman:</strong>
                    <a href="{{ asset('storage/' . $reservation->surat_peminjaman_path) }}" target="_blank">Lihat Berkas</a>
                </p>

                @if ($reservation->proposal_kegiatan_path)
                    <p>
                        <strong>Proposal Kegiatan:</strong>
                        <a href="{{ asset('storage/' . $reservation->proposal_kegiatan_path) }}" target="_blank">Lihat Berkas</a>
                    </p>
                @endif

                @if ($reservation->status === 'dibatalkan' && $reservation->alasan_pembatalan)
                    <p><strong>Alasan Dibatalkan:</strong> {{ $reservation->alasan_pembatalan }}</p>
                @endif

                @if ($reservation->status === 'ditolak' && $reservation->alasan_pembatalan)
                    <p><strong>Alasan Ditolak:</strong> {{ $reservation->alasan_pembatalan }}</p>
                @endif

                @if (in_array($reservation->status, ['menunggu', 'disetujui']) && $reservation->user_id === auth()->id())
                    <div class="form-group" style="margin-top: 1rem;">
                        @if ($reservation->bisaDibatalkanOlehUser())
                            {{-- Aktif: form tersembunyi, disubmit lewat JS setelah alasan diisi di popup --}}
                            <form
                                id="cancel-form"
                                action="{{ route('reservations.cancel', $reservation) }}"
                                method="POST"
                            >
                                @csrf
                                @method('PATCH')

                                <input
                                    type="hidden"
                                    name="alasan"
                                    id="alasan-cancel"
                                >
                            </form>
                            <button
                                type="button"
                                class="btn-submit"
                                style="background-color:#c0392b;"
                                onclick='confirmWithInput(
                                    "cancel-form",
                                    "Batalkan Peminjaman?",
                                    "Yakin ingin membatalkan? Tindakan ini tidak dapat diurungkan.",
                                    "Alasan pembatalan",
                                    "Contoh: Acara ditunda.",
                                    "alasan-cancel"
                                )'
                            >
                                Batalkan Reservasi
                            </button>
                        @else
                            <button type="button" class="btn-submit" disabled style="background-color:#c9c9c9; color:#666; cursor:not-allowed;">
                                Batalkan Reservasi
                            </button>
                            <p class="field-hint" style="margin-top:0.5rem; color:#c0392b;">
                                Reservasi ini sudah tidak dapat dibatalkan (kurang dari 7 hari sebelum jadwal kegiatan).
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </section>
    </main>
</body>
</html>