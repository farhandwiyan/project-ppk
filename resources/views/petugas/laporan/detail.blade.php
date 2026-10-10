<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan Kerusakan - KARSA</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/sweetalert-helpers.js') }}"></script>
</head>
<body class="bg-gray-50 p-8">

    @php
        $badgeColor = match($laporan->status) {
            'baru'     => 'bg-blue-100 text-blue-700',
            'diproses' => 'bg-orange-100 text-orange-600',
            'selesai'  => 'bg-green-100 text-green-700',
            'ditolak'  => 'bg-red-100 text-red-700',
            default    => 'bg-gray-100 text-gray-600',
        };

        $fotos = is_array($laporan->bukti_kerusakan) ? $laporan->bukti_kerusakan : [];
    @endphp

    <div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-sm border border-gray-100">

        <!-- Header -->
        <div class="mb-8 border-b pb-4 flex justify-between items-start">
            <div>
                <p class="text-sm font-semibold text-gray-600 mb-1">Detail Laporan Kerusakan</p>
                <h1 class="text-3xl font-bold text-[#0F172A] mb-2">{{ $laporan->fasilitas->nama ?? 'Nama Fasilitas' }}</h1>
                <p class="text-xs text-red-500 font-medium">
                    *Periksa deskripsi dan bukti kerusakan sebelum mengubah status laporan
                </p>
            </div>
            <span class="{{ $badgeColor }} px-4 py-1.5 rounded text-xs font-semibold uppercase">
                {{ $laporan->status }}
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

        <!-- 1. Informasi Fasilitas -->
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Informasi Fasilitas</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Fasilitas</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $laporan->fasilitas->nama ?? '-' }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Tipe Fasilitas</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ ucfirst($laporan->fasilitas->tipe_fasilitas ?? '-') }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Lokasi</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $laporan->fasilitas->lokasi ?? '-' }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Data Pelapor -->
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Data Pelapor</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Pelapor</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $laporan->nama_pelapor }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Email</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $laporan->email }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $laporan->nomor_telepon }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Isi Laporan -->
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Isi Laporan</h2>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 mb-2">Waktu Laporan</label>
                <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                    {{ $laporan->created_at->format('d M Y, H:i') }} WIB
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-700 mb-2">Deskripsi Kerusakan</label>
                <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-3 min-h-[80px] whitespace-pre-line">{{ $laporan->deskripsi }}</div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Bukti Kerusakan</label>
                <div class="flex flex-wrap gap-3">
                    @forelse ($fotos as $foto)
                        <a href="{{ asset('storage/' . $foto) }}" target="_blank">
                            <img src="{{ asset('storage/' . $foto) }}" alt="Bukti kerusakan" class="w-32 h-32 object-cover rounded-lg border border-gray-200">
                        </a>
                    @empty
                        <p class="text-xs text-gray-500">Tidak ada foto bukti.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 4. Hasil Pemrosesan -->
        @if ($laporan->status !== 'baru')
        <div class="border border-gray-200 rounded-lg p-6 mb-8">
            <h2 class="text-sm font-bold text-gray-800 mb-4 uppercase tracking-wide">Hasil Pemrosesan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Diproses Oleh</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $laporan->petugas->nama ?? '-' }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Waktu Pemrosesan</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $laporan->diproses_pada ? $laporan->diproses_pada->format('d M Y, H:i') . ' WIB' : '-' }}
                    </div>
                </div>
            </div>

            @if ($laporan->status === 'selesai')                    <label class="block text-xs font-semibold text-gray-700 mb-2">Catatan Penyelesaian</label>
                    <div class="bg-green-50 border border-green-200 text-gray-700 text-sm rounded-md px-3 py-3 whitespace-pre-line">{{ $laporan->catatan_penyelesaian }}</div>
                </div>
            @endif

            @if ($laporan->status === 'ditolak')
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Alasan Penolakan</label>
                    <div class="bg-red-50 border border-red-200 text-gray-700 text-sm rounded-md px-3 py-3 whitespace-pre-line">{{ $laporan->alasan_penolakan }}</div>
                </div>
            @endif
        </div>
        @endif

        <!-- Tombol Aksi Bawah -->
        <div class="flex justify-between items-center pt-4 border-t">
            <a href="{{ route('petugas.laporan.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                Kembali
            </a>

            @if (count($laporan->statusTujuan()) > 0)
            <div class="flex gap-3">

                @if ($laporan->bisaDiubahKe('diproses'))
                    <form id="form-proses" action="{{ route('petugas.laporan.status', $laporan->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="diproses">
                    </form>

                    <button
                        type="button"
                        onclick="confirmPopUp('form-proses', 'Proses Laporan?', 'Status laporan akan diubah menjadi diproses.')"
                        class="px-8 py-2 bg-[#1A2254] text-white rounded-lg font-semibold hover:bg-blue-900 cursor-pointer"
                    >
                        Proses
                    </button>
                @endif

                @if ($laporan->bisaDiubahKe('selesai'))
                    <form id="form-selesai" action="{{ route('petugas.laporan.status', $laporan->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="selesai">
                        <input type="hidden" name="catatan_penyelesaian" id="catatan-selesai">
                    </form>

                    <button
                        type="button"
                        onclick="confirmWithInput('form-selesai', 'Selesaikan Laporan?', 'Masukkan catatan penyelesaian laporan ini.', 'Catatan Penyelesaian', 'Contoh: Lampu proyektor sudah diganti', 'catatan-selesai')"
                        class="px-8 py-2 bg-[#0F766E] text-white rounded-lg font-semibold hover:bg-teal-800 cursor-pointer"
                    >
                        Selesai
                    </button>
                @endif

                @if ($laporan->bisaDiubahKe('ditolak'))
                    <form id="form-tolak" action="{{ route('petugas.laporan.status', $laporan->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="ditolak">
                        <input type="hidden" name="alasan_penolakan" id="alasan-tolak">
                    </form>

                    <button
                        type="button"
                        onclick="confirmWithInput('form-tolak', 'Tolak Laporan?', 'Masukkan alasan penolakan laporan ini.', 'Alasan Penolakan', 'Contoh: Kerusakan tidak ditemukan saat diperiksa', 'alasan-tolak')"
                        class="px-8 py-2 bg-[#B91C1C] text-white rounded-lg font-semibold hover:bg-red-800 cursor-pointer"
                    >
                        Tolak
                    </button>
                @endif
            </div>
            @else
                <div class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg font-semibold uppercase text-sm">
                    Status: {{ $laporan->status }}
                </div>
            @endif
        </div>

    </div>

</body>
</html>