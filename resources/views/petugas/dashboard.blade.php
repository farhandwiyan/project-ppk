<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - KARSA</title>
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
                <a href="{{ route('petugas.home') ?? '#' }}" class="flex items-center px-4 py-3 bg-sidebar-active rounded-lg text-sm text-gray-100 font-medium">
                    Antrean Reservasi Fasilitas
                </a>

                <!-- MENU BARU: Riwayat Reservasi -->
                <a href="{{ route('petugas.riwayat') }}" class="flex items-center px-4 py-3 text-sm text-gray-400 hover:text-white hover:bg-sidebar-active rounded-lg transition-colors">
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
        
        <!-- Sidebar Footer (Logout) -->
        <div class="p-4 border-t border-gray-700">
            <!-- Sesuaikan dengan route logout milik temanmu -->
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
        
        <!-- Topbar (Integrasi dari kode temanmu) -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Halo, {{ auth()->user()->nama ?? 'Petugas' }}</h1>
                <p class="text-sm text-gray-500 mt-1">Selamat datang kembali di KARSA</p>
            </div>
            <!-- Sesuaikan dengan route update-user milik temanmu -->
            <a href="#" class="bg-[#1A2254] hover:bg-blue-900 text-white text-sm font-medium px-6 py-2.5 rounded shadow-sm transition-colors">
                Edit Profil
            </a>
        </div>

        <!-- Session Alerts (Integrasi dari kode temanmu) -->
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

        <!-- Stats Cards (Dari Figma) -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Antrean Reservasi</h3>
                <p class="text-4xl font-bold text-gray-900 mb-4">{{ $antreanReservasi }}</p>
                <div class="flex items-center justify-between">
                    <span class="bg-gray-100 text-gray-700 text-xs px-3 py-1 rounded-full font-medium">Fasilitas</span>
                    <span class="text-xs text-gray-500"><span class="text-red-500 mr-1">*</span>Menunggu Verifikasi</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Antrean Kerusakan</h3>
                <p class="text-4xl font-bold text-gray-900 mb-4">{{ $antreanKerusakan }}</p>
                <div class="flex items-center justify-between">
                    <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full font-medium">Kerusakan</span>
                    <span class="text-xs text-gray-500"><span class="text-red-500 mr-1">*</span>Menunggu Verifikasi</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Sedang Diperbaiki</h3>
                <p class="text-4xl font-bold text-gray-900 mb-4">{{ $sedangDiperbaiki }}</p>
                <div class="flex items-center justify-between">
                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">Teknisi</span>
                    <span class="text-xs text-gray-500"><span class="text-red-500 mr-1">*</span>Sedang Diperbaiki</span>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-xs font-semibold text-gray-600 mb-2 uppercase tracking-wide">Dibatalkan</h3>
                <p class="text-4xl font-bold text-gray-900 mb-4">{{ $antreanBatal }}</p>
                <div class="flex items-center justify-between">
                    <span class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full font-medium">Fasilitas</span>
                    <span class="text-xs text-gray-500"><span class="text-red-500 mr-1">*</span>Menunggu Verifikasi</span>
                </div>
            </div>
        </div>

        <!-- Table Section (Dari Figma) -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6">
                <h2 class="text-2xl font-serif text-gray-800 mb-2">Antrean Verifikasi Reservasi Fasilitas</h2>
                <p class="text-sm text-gray-500 mb-6"><span class="text-red-500 mr-1">*</span>Periksa Persyaratan Peminjaman</p>
                
                <div class="mb-4">
    <form method="GET" action="{{ url()->current() }}" class="flex gap-4 mb-4">
    
    <!-- Dropdown Filter Tipe Fasilitas -->
    <select name="tipe_fasilitas" onchange="this.form.submit()" 
            class="px-5 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 font-medium hover:bg-gray-50 transition-colors bg-white cursor-pointer outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Tipe Fasilitas</option>
        @if(isset($daftarTipe))
            @foreach($daftarTipe as $tipe)
                <option value="{{ $tipe }}" {{ request('tipe_fasilitas') == $tipe ? 'selected' : '' }}>
                    {{ ucfirst($tipe) }}
                </option>
            @endforeach
        @endif
    </select>

    <!-- Dropdown Filter Lokasi -->
    <select name="lokasi" onchange="this.form.submit()" 
            class="px-5 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 font-medium hover:bg-gray-50 transition-colors bg-white cursor-pointer outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Semua Lokasi</option>
        @if(isset($daftarLokasi))
            @foreach($daftarLokasi as $lokasi)
                <option value="{{ $lokasi }}" {{ request('lokasi') == $lokasi ? 'selected' : '' }}>
                    {{ $lokasi }}
                </option>
            @endforeach
        @endif
    </select>

    <!-- Dropdown Urutan Waktu Acara -->
    <select name="sort" onchange="this.form.submit()" 
            class="px-5 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 font-medium hover:bg-gray-50 transition-colors bg-white cursor-pointer outline-none focus:ring-2 focus:ring-blue-500">
        <option value="terbaru" {{ request('sort') == 'terbaru' ? 'selected' : '' }}>Waktu Acara: Terbaru</option>
        <option value="terlama" {{ request('sort') == 'terlama' ? 'selected' : '' }}>Waktu Acara: Terlama</option>
    </select>

    <!-- Tombol Reset -->
    @if(request()->filled('tipe_fasilitas') || request()->filled('lokasi') || request()->filled('sort'))
        <a href="{{ url()->current() }}" 
           class="px-5 py-2 border border-red-200 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors flex items-center">
            Reset Filter
        </a>
    @endif

</form>
</div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="bg-gray-200 text-gray-600">
                            <th class="p-4 font-semibold text-left">Pemohon</th>
                            <th class="p-4 font-semibold text-left">Instansi</th>
                            <th class="p-4 font-semibold text-left">Fasilitas</th>
                            <th class="p-4 font-semibold text-left">Tanggal & Durasi</th>
                            <th class="p-4 font-semibold w-1/6">Status</th>
                            <th class="p-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($reservations as $res)
                        <tr class="hover:bg-gray-50">
                            <!-- 1. Kolom Pemohon -->
                            <td class="p-4 align-top">
                                <div class="font-bold text-gray-900">{{ $res->nama_pemohon }}</div>
                            </td>
                            
                            <!-- 2. Kolom Instansi -->
                            <td class="p-4 align-top">
                                <div class="text-gray-600 text-sm">{{ $res->instansi_pemohon }}</div>
                            </td>
                            
                            <!-- 3. Kolom Fasilitas -->
                            <td class="p-4 align-top">
                                <div class="font-bold text-gray-900">{{ $res->fasilitas->nama ?? 'Nama Fasilitas' }}</div>
                            </td>
                            
                            <!-- 4. Kolom Tanggal & Jam -->
                            <td class="p-4 align-top">
                                <div class="font-medium text-gray-900 mb-1">{{ \Carbon\Carbon::parse($res->tanggal)->format('d M Y') }}</div>
                                <div class="text-gray-500 text-xs">
                                    {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }} WIB
                                </div>
                            </td>
                            
                            <!-- 5. Kolom Status -->
                            <td class="p-4 align-middle">
                                @php
                                    $badgeColor = match(strtolower($res->status)) {
                                        'disetujui' => 'bg-green-100 text-green-700',
                                        'ditolak'   => 'bg-red-100 text-red-700',
                                        default     => 'bg-orange-100 text-orange-600',
                                    };
                                @endphp
                                <span class="{{ $badgeColor }} px-4 py-1.5 rounded text-xs font-semibold uppercase">
                                    {{ $res->status }}
                                </span>
                            </td>
                            
                            <!-- 6. Kolom Aksi -->
                            <td class="p-4 align-middle text-center">
                                <a href="{{ route('petugas.reservasi.show', $res->id) }}" class="px-4 py-2 text-xs border border-gray-300 text-gray-700 rounded hover:bg-gray-100 font-medium inline-block">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">Belum ada data reservasi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

</body>
</html>