<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kerusakan - KARSA</title>
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
                <a href="{{ route('petugas.riwayat') }}" class="flex items-center px-4 py-3 text-sm text-gray-400 hover:text-white hover:bg-sidebar-active rounded-lg transition-colors">
                    Riwayat Reservasi
                </a>
                <a href="{{ route('petugas.laporan.index') }}" class="flex items-center px-4 py-3 bg-sidebar-active rounded-lg text-sm text-gray-100 font-medium">
                    Laporan Kerusakan
                </a>
                <a href="{{ route('petugas.fasilitas.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-400 hover:text-white hover:bg-sidebar-active rounded-lg transition-colors">
                    Status Fasilitas
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

        <div class="mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Laporan Kerusakan</h1>
            <p class="text-sm text-gray-500 mt-1">Periksa, proses, dan validasi laporan kerusakan fasilitas yang masuk</p>
        </div>

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
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Baru</h3>
                <p class="text-4xl font-bold text-gray-900">{{ $jumlahPerStatus['baru'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Diproses</h3>
                <p class="text-4xl font-bold text-gray-900">{{ $jumlahPerStatus['diproses'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Selesai</h3>
                <p class="text-4xl font-bold text-gray-900">{{ $jumlahPerStatus['selesai'] ?? 0 }}</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Ditolak</h3>
                <p class="text-4xl font-bold text-gray-900">{{ $jumlahPerStatus['ditolak'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-serif text-gray-800 mb-4">Daftar Laporan Kerusakan</h2>

                <form method="GET" action="{{ route('petugas.laporan.index') }}" class="flex gap-4">
                    <select name="status" onchange="this.form.submit()"
                            class="px-5 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 font-medium hover:bg-gray-50 transition-colors bg-white cursor-pointer outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        @foreach($daftarStatus as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>

                    @if(request()->filled('status'))
                        <a href="{{ route('petugas.laporan.index') }}"
                           class="px-5 py-2 border border-red-200 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors flex items-center">
                            Reset Filter
                        </a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="p-4 font-semibold text-left">Pelapor</th>
                            <th class="p-4 font-semibold text-left">Fasilitas</th>
                            <th class="p-4 font-semibold text-left">Deskripsi</th>
                            <th class="p-4 font-semibold text-left">Waktu Laporan</th>
                            <th class="p-4 font-semibold w-1/6">Status</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($laporan as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="p-4 align-top">
                                <div class="font-bold text-gray-900">{{ $item->nama_pelapor }}</div>
                                <div class="text-gray-500 text-xs">{{ $item->email }}</div>
                            </td>
                            <td class="p-4 align-top">
                                <div class="font-bold text-gray-900">{{ $item->fasilitas->nama ?? '-' }}</div>
                                <div class="text-gray-500 text-xs">{{ $item->fasilitas->lokasi ?? '' }}</div>
                            </td>
                            <td class="p-4 align-top text-gray-600">
                                {{ \Illuminate\Support\Str::limit($item->deskripsi, 70) }}
                            </td>
                            <td class="p-4 align-top">
                                <div class="font-medium text-gray-900 mb-1">{{ $item->created_at->format('d M Y') }}</div>
                                <div class="text-gray-500 text-xs">{{ $item->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="p-4 align-middle">
                                @php
                                    $badgeColor = match($item->status) {
                                        'baru'     => 'bg-blue-100 text-blue-700',
                                        'diproses' => 'bg-orange-100 text-orange-600',
                                        'selesai'  => 'bg-green-100 text-green-700',
                                        'ditolak'  => 'bg-red-100 text-red-700',
                                        default    => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="{{ $badgeColor }} px-4 py-1.5 rounded text-xs font-semibold uppercase">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="p-4 align-middle text-center">
                                <a href="{{ route('petugas.laporan.show', $item->id) }}" class="px-4 py-2 text-xs border border-gray-300 text-gray-700 rounded hover:bg-gray-100 font-medium inline-block">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">Belum ada laporan kerusakan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $laporan->links() }}
            </div>
        </div>
    </main>

</body>
</html>