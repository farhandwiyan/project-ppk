<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - KARSA</title>
    <link rel="stylesheet" href="{{ asset('css/laporan/detail.css') }}">
</head>
<body>
    <main class="page-content">

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="btn-kembali">
            <a href="{{ route('reservations.riwayat') }}" class="btn-secondary">
                &laquo; Kembali
            </a>
        </div>

        <section class="laporan-card">

            <div class="laporan-header">
                <h2>Detail Laporan Kerusakan</h2>

                <p>Status:
                    <strong>
                        {{ ucfirst($laporan->status) }}
                    </strong>
                </p>
            </div>

            <div class="laporan-form">
                <p>
                    <strong>Fasilitas:</strong>
                    {{ $laporan->fasilitas->nama ?? '-' }}
                </p>

                <p>
                    <strong>Nama Pelapor:</strong>
                    {{ $laporan->nama_pelapor }}
                </p>

                <p>
                    <strong>Email:</strong>
                    {{ $laporan->email }}
                </p>

                <p>
                    <strong>Nomor Telepon:</strong>
                    {{ $laporan->nomor_telepon }}
                </p>

                <p>
                    <strong>Waktu Laporan:</strong>
                    {{ $laporan->created_at->format('d M Y, H:i') }} WIB
                </p>

                <p>
                    <strong>Deskripsi:</strong>
                    {{ $laporan->deskripsi }}
                </p>

                <div class="bukti-section">
                    <strong>Bukti Kerusakan:</strong>

                    @php
                        $fotos = $laporan->bukti_kerusakan;
                        if (is_string($fotos)) {
                            $fotos = json_decode($fotos, true);
                        }
                        $fotos = is_array($fotos) ? $fotos : [];
                    @endphp

                    <div class="bukti-foto">
                        @forelse ($fotos as $foto)
                            <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                                <img
                                    src="{{ asset('storage/' . $foto) }}"
                                    alt="Bukti kerusakan"
                                >
                            </a>
                        @empty
                            <p>Tidak ada foto bukti.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>