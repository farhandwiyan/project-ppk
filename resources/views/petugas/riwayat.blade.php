<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Reservasi - KARSA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-sidebar { background-color: #0A0F2C; }
        .bg-sidebar-active { background-color: #1A2254; }
    </style>
</head>
<body class="bg-gray-50 flex h-screen font-sans overflow-hidden">

    <!-- ===== Sidebar ===== -->
    <aside class="w-64 bg-sidebar text-white flex flex-col h-full shadow-lg justify-between">
        <div>
            <div class="p-8 pb-12">
                <span class="text-3xl font-serif font-bold tracking-wide">Karsa</span>
            </div>
            <nav class="px-4 space-y-3">
                <a href="{{ route('petugas.home') }}" class="flex items-center px-4 py-3 text-sm text-gray-400 hover:text-white hover:bg-sidebar-active rounded-lg transition-colors">
                    Antrean Reservasi Fasilitas
                </a>
                <a href="{{ route('petugas.riwayat') }}" class="flex items-center px-4 py-3 bg-sidebar-active rounded-lg text-sm text-gray-100 font-medium">
                    Riwayat Reservasi
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-400 hover:text-white hover:bg-sidebar-active rounded-lg transition-colors">
                    Laporan Kerusakan
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-400 hover:text-white hover:bg-sidebar-active rounded-lg transition-colors">
                    Status Fasilitas
                </a>
                <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-400 hover:text-white hover:bg-sidebar-active rounded-lg transition-colors">
                    Jadwal Perbaikan
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-700">
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-3 text-sm text-[#E08F8F] hover:text-red-400 font-medium transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- ===== Main content ===== -->
    <main class="flex-1 overflow-y-auto p-8">

        <!-- Topbar -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Riwayat Reservasi</h1>
                <p class="text-sm text-gray-500 mt-1">Reservasi yang sudah disetujui, ditolak, atau dibatalkan pengguna</p>
            </div>
            <a href="{{ route('petugas.home') }}" class="bg-[#1A2254] hover:bg-blue-900 text-white text-sm font-medium px-6 py-2.5 rounded shadow-sm transition-colors">
                &larr; Kembali ke Antrean
            </a>
        </div>

        <!-- Session Alerts -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Disetujui</h3>
                <p class="text-4xl font-bold text-gray-900 mb-4">{{ $totalDisetujui }}</p>
                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">Reservasi</span>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Ditolak</h3>
                <p class="text-4xl font-bold text-gray-900 mb-4">{{ $totalDitolak }}</p>
                <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-medium">Reservasi</span>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Dibatalkan</h3>
                <p class="text-4xl font-bold text-gray-900 mb-4">{{ $totalDibatalkan }}</p>
                <span class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full font-medium">Oleh Pengguna</span>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-serif text-gray-800 mb-2">Daftar Riwayat Reservasi</h2>
                <p class="text-sm text-gray-500 mb-6"><span class="text-red-500 mr-1">*</span>Filter berdasarkan status reservasi</p>

                @php $aktif = strtolower(request('status', 'semua')); @endphp
                <div class="flex flex-wrap gap-3">
                    @foreach ([
                        'semua'      => 'Semua',
                        'disetujui'  => 'Disetujui',
                        'ditolak'    => 'Ditolak',
                        'dibatalkan' => 'Dibatalkan',
                    ] as $key => $label)
                        <a href="{{ route('petugas.riwayat', $key === 'semua' ? [] : ['status' => $key]) }}"
                           class="px-5 py-2 rounded-lg text-sm font-medium border transition-colors
                                  {{ $aktif === $key
                                      ? 'bg-[#1A2254] text-white border-[#1A2254]'
                                      : 'border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="p-4 font-semibold">Pemohon</th>
                            <th class="p-4 font-semibold">Instansi</th>
                            <th class="p-4 font-semibold">Fasilitas</th>
                            <th class="p-4 font-semibold">Tanggal & Durasi</th>
                            <th class="p-4 font-semibold">Status</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reservations as $res)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 align-top">
                                <div class="font-bold text-gray-900">{{ $res->nama_pemohon }}</div>
                            </td>
                            <td class="p-4 align-top">
                                <div class="text-gray-600">{{ $res->instansi_pemohon }}</div>
                            </td>
                            <td class="p-4 align-top">
                                <div class="font-bold text-gray-900">{{ $res->fasilitas->nama ?? '-' }}</div>
                            </td>
                            <td class="p-4 align-top">
                                <div class="font-medium text-gray-900 mb-1">{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</div>
                                <div class="text-gray-500 text-xs">
                                    {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }} WIB
                                </div>
                            </td>

                            <td class="p-4 align-middle">
                                @php
                                    $badgeColor = match(strtolower($res->status)) {
                                        'disetujui'  => 'bg-green-100 text-green-700',
                                        'ditolak'    => 'bg-red-100 text-red-700',
                                        'dibatalkan' => 'bg-orange-100 text-orange-600',
                                        default      => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="{{ $badgeColor }} px-4 py-1.5 rounded text-xs font-semibold uppercase whitespace-nowrap">
                                    {{ $res->status }}
                                </span>
                            </td>
                            <td class="p-4 align-middle text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('petugas.reservasi.show', $res->id) }}"
                                       class="px-4 py-2 text-xs border border-gray-300 text-gray-700 rounded hover:bg-gray-100 font-medium">
                                        Lihat Detail
                                    </a>
                                    <form action="{{ route('petugas.riwayat.destroy', $res->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus riwayat reservasi ini? Data tidak dapat dikembalikan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-2 text-xs border border-red-300 text-red-600 rounded hover:bg-red-50 font-medium">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">Belum ada riwayat reservasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if(method_exists($reservations, 'links'))
                <div class="mt-6">
                    {{ $reservations->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </main>

</body>
</html>