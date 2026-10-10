<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Status Fasilitas - KARSA</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/sweetalert-helpers.js') }}"></script>
</head>
<body class="bg-gray-50 p-4 md:p-8">

    @php
        $statusFasilitas = strtolower($fasilitas->status ?? 'aktif');

        $badgeColor = match($statusFasilitas) {
            'aktif'    => 'bg-green-100 text-green-700',
            'nonaktif' => 'bg-red-100 text-red-700',
            default    => 'bg-yellow-100 text-yellow-700', // dalam_perbaikan
        };

        $labelStatus = match($statusFasilitas) {
            'aktif'           => 'Aktif',
            'nonaktif'        => 'Nonaktif',
            'dalam_perbaikan' => 'Dalam Perbaikan',
            default           => ucwords(str_replace('_', ' ', $statusFasilitas)),
        };

        // kolom kapasitas: alat = jumlah unit, selain itu = jumlah orang
        $satuanKapasitas = strtolower($fasilitas->tipe_fasilitas) === 'alat' ? 'Unit' : 'Orang';
    @endphp

    <div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-sm border border-gray-100">

        {{-- Header --}}
        <div class="mb-8 border-b pb-4 flex justify-between items-start gap-4">
            <div>
                <p class="text-sm font-semibold text-gray-600 mb-1">Ubah Status Fasilitas</p>
                <h1 class="text-3xl font-bold text-[#0F172A] mb-2">{{ $fasilitas->nama }}</h1>
                <p class="text-xs text-red-500 font-medium">
                    *Pastikan seluruh reservasi yang terdampak telah ditangani
                    sebelum fasilitas dinonaktifkan atau diperbaiki.
                </p>
            </div>
            <span class="{{ $badgeColor }} px-4 py-1.5 rounded text-xs font-semibold uppercase">
                {{ $labelStatus }}
            </span>
        </div>

        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    messagePopUp('Berhasil', @json(session('success')), 'success');
                });
            </script>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 1. Informasi Fasilitas --}}
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Informasi Fasilitas</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Fasilitas</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $fasilitas->nama }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Tipe Fasilitas</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ ucfirst($fasilitas->tipe_fasilitas) }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Lokasi</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $fasilitas->lokasi }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Kapasitas</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $fasilitas->kapasitas }} {{ $satuanKapasitas }}
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. Reservasi Fasilitas  --}}
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 mb-4">
                <div>
                    <h2 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Reservasi Fasilitas</h2>
                    <p class="text-xs text-gray-500 mt-1">
                        Periksa reservasi yang masih menunggu persetujuan atau sudah disetujui.
                        Reservasi yang terdampak perlu ditangani sebelum status fasilitas diubah.
                    </p>
                </div>

                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold self-start sm:self-auto">
                    {{ $reservasi->count() }} Reservasi
                </span>
            </div>

            @forelse ($reservasi as $res)
                @php
                    $statusReservasi = strtolower($res->status);

                    $statusColor = match ($statusReservasi) {
                        'menunggu'              => 'bg-orange-100 text-orange-700',
                        'disetujui'             => 'bg-green-100 text-green-700',
                        'dibatalkan', 'ditolak' => 'bg-red-100 text-red-700',
                        default                 => 'bg-gray-100 text-gray-700',
                    };
                @endphp

                <div class="border border-gray-200 rounded-lg p-4 mb-3 last:mb-0">
                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">

                        {{-- Informasi Reservasi --}}
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $res->nama_kegiatan }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Pemohon: {{ $res->nama_pemohon }}</p>

                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Tanggal Reservasi</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($res->tanggal)->translatedFormat('d F Y') }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-xs text-gray-500 mb-1">Waktu Reservasi</p>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }}
                                        -
                                        {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }} WIB
                                    </p>
                                </div>
                            </div>

                            <div class="mt-3">
                                <span class="{{ $statusColor }} px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ ucfirst($res->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex w-full flex-col gap-2 lg:w-56">

                            <a
                                href="{{ route('petugas.reservasi.show', $res->id) }}"
                                class="flex w-full items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50"
                            >
                                Lihat Detail
                            </a>

                            @if ($statusReservasi === 'menunggu')

                                <form
                                    id="form-tolak-{{ $res->id }}"
                                    action="{{ route('petugas.reservasi.tolak', $res->id) }}"
                                    method="POST"
                                    class="hidden"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" id="alasan-tolak-reservasi-{{ $res->id }}" name="alasan">
                                </form>

                                <button
                                    type="button"
                                    onclick="confirmWithInput(
                                        'form-tolak-{{ $res->id }}',
                                        'Tolak Reservasi?',
                                        'Masukkan alasan penolakan reservasi ini.',
                                        'Alasan Penolakan',
                                        'Contoh: Fasilitas sedang digunakan acara lain',
                                        'alasan-tolak-reservasi-{{ $res->id }}'
                                    )"
                                    class="flex w-full items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-red-700"
                                >
                                    Tolak
                                </button>

                            @elseif ($statusReservasi === 'disetujui')

                                <form
                                    id="form-batal-{{ $res->id }}"
                                    action="{{ route('petugas.reservasi.fasilitas.batalkan', $res->id) }}"
                                    method="POST"
                                    class="hidden"
                                >
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" id="alasan-batal-reservasi-{{ $res->id }}" name="alasan">
                                </form>

                                {{-- Tombol Batalkan --}}
                                <button
                                    type="button"
                                    onclick="confirmWithInput(
                                        'form-batal-{{ $res->id }}',
                                        'Batalkan Reservasi?',
                                        'Masukkan alasan pembatalan yang akan dilihat oleh pengguna.',
                                        'Alasan Pembatalan',
                                        'Contoh: Fasilitas sedang mengalami kerusakan',
                                        'alasan-batal-reservasi-{{ $res->id }}'
                                    )"
                                    class="flex w-full items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-center text-sm font-semibold text-white hover:bg-red-700"
                                >
                                    Batalkan
                                </button>

                            @endif

                        </div>
                    </div>
                </div>

            @empty
                <div class="text-center py-8 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600 font-medium">Tidak ada reservasi aktif untuk fasilitas ini.</p>
                    <p class="text-xs text-gray-500 mt-1">Belum ada reservasi yang perlu ditindaklanjuti sebelum status diubah.</p>
                </div>
            @endforelse
        </div>

        {{-- 3. Pengaturan Status --}}
        <div class="border border-gray-200 rounded-lg p-6 mb-8">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Pengaturan Status Fasilitas</h2>

            <form id="form-status" action="{{ route('petugas.fasilitas.updateStatus', $fasilitas->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-5">
                    <label for="status" class="block text-xs font-semibold text-gray-700 mb-2">Status Baru</label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-gray-300"
                    >
                        <option value="aktif" {{ $statusFasilitas === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="dalam_perbaikan" {{ $statusFasilitas === 'dalam_perbaikan' ? 'selected' : '' }}>Sedang Diperbaiki</option>
                    </select>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-xs text-yellow-800">
                        <strong>Perhatian:</strong>
                        Pastikan reservasi yang terdampak sudah ditolak atau dibatalkan.
                    </p>
                </div>
            </form>
        </div>

        {{-- Tombol Aksi Bawah --}}
        <div class="flex flex-col-reverse sm:flex-row justify-between items-stretch sm:items-center gap-3 pt-4 border-t">
            <a
                href="{{ route('petugas.fasilitas.index') }}"
                class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50 text-center transition"
            >
                Kembali
            </a>

            <button
                type="button"
                onclick="confirmPopUp('form-status', 'Simpan Perubahan Status?', 'Pastikan semua reservasi yang terdampak sudah ditangani.')"
                class="px-8 py-2 bg-[#1A2254] text-white rounded-lg font-semibold hover:bg-blue-900 cursor-pointer transition"
            >
                Simpan Perubahan
            </button>
        </div>

    </div>

</body>
</html>