<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Fasilitas - KARSA</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .bg-sidebar {
            background-color: #0A0F2C;
        }

        .bg-sidebar-active {
            background-color: #1A2254;
        }
    </style>
</head>

<body class="bg-gray-50 flex min-h-screen font-sans">

    {{-- SIDEBAR --}}    
    <aside class="fixed top-0 left-0 w-64 h-screen bg-sidebar
                text-white flex flex-col shadow-lg justify-between
                overflow-y-auto">


        <div>
            <div class="p-8 pb-12">
                <span class="text-3xl font-serif font-bold tracking-wide">
                    Karsa
                </span>
            </div>

            <nav class="px-4 space-y-3">

                <a href="{{ route('petugas.home') }}"
                   class="flex items-center px-4 py-3 text-sm text-gray-400
                          hover:text-white hover:bg-sidebar-active
                          rounded-lg transition-colors">
                    Antrean Reservasi Fasilitas
                </a>

                <a href="{{ route('petugas.riwayat') }}"
                   class="flex items-center px-4 py-3 text-sm text-gray-400
                          hover:text-white hover:bg-sidebar-active
                          rounded-lg transition-colors">
                    Riwayat Reservasi
                </a>

                <a href="{{ route('petugas.laporan.index') }}"
                   class="flex items-center px-4 py-3 text-sm text-gray-400
                          hover:text-white hover:bg-sidebar-active
                          rounded-lg transition-colors">
                    Laporan Kerusakan
                </a>

                <a href="{{ route('petugas.fasilitas.index') }}"
                   class="flex items-center px-4 py-3 bg-sidebar-active
                          rounded-lg text-sm text-white font-medium">
                    Status Fasilitas
                </a>
            </nav>
        </div>

        {{-- LOGOUT --}}
        <div class="p-4 border-t border-gray-700">
            <form action="{{ route('logout') }}" method="POST" class="w-full">
                @csrf

                <button type="submit"
                        class="w-full text-left px-4 py-3 text-sm
                               text-[#E08F8F] hover:text-red-400
                               font-medium transition-colors">
                    Logout
                </button>
            </form>
        </div>

    </aside>


    {{-- KONTEN UTAMA --}}
    <main class="ml-64 flex-1 min-w-0 h-screen overflow-y-auto p-6 md:p-8">

        {{-- HEADER --}}
        <div class="flex flex-col sm:flex-row sm:justify-between
                    sm:items-start gap-4 mb-8">

            <div>
                <h1 class="text-2xl font-semibold text-gray-900">
                    Status Fasilitas
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Halo, {{ auth()->user()->nama ?? 'Petugas' }}.
                    Kelola dan pantau status fasilitas KARSA.
                </p>
            </div>

            <a href="#"
               class="bg-[#1A2254] hover:bg-blue-900 text-white
                      text-sm font-medium px-6 py-2.5 rounded
                      shadow-sm transition-colors text-center">
                Status Fasilitas
            </a>

        </div>


        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700
                        px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700
                        px-4 py-3 rounded-lg mb-6 text-sm">
                {{ session('error') }}
            </div>
        @endif


        
        {{-- RINGKASAN STATUS FASILITAS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 mb-8">

            {{-- FASILITAS AKTIF --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Fasilitas Aktif
                    </p>
                </div>

                <p class="text-3xl font-bold text-gray-900">
                    {{ $totalAktif }}
                </p>

                <p class="text-xs text-green-600 mt-3">
                    Fasilitas berstatus aktif
                </p>
            </div>


            {{-- FASILITAS NONAKTIF --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Fasilitas Nonaktif
                    </p>
                </div>

                <p class="text-3xl font-bold text-gray-900">
                    {{ $totalNonaktif }}
                </p>

                <p class="text-xs text-gray-500 mt-3">
                    Fasilitas berstatus nonaktif
                </p>
            </div>


            {{-- SEDANG DALAM PERBAIKAN --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">
                        Sedang dalam Perbaikan
                    </p>
                </div>

                <p class="text-3xl font-bold text-gray-900">
                    {{ $totalPerbaikan }}
                </p>

                <p class="text-xs text-orange-600 mt-3">
                    Fasilitas sedang diperbaiki
                </p>
            </div>

        </div>



        {{-- TABEL STATUS FASILITAS --}}
        <div class="bg-white rounded-xl shadow-sm
                    border border-gray-100 p-5 md:p-6">

            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    Daftar Status Fasilitas
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Cari fasilitas dan gunakan filter untuk melihat
                    informasi berdasarkan tipe atau lokasi.
                </p>
            </div>


            {{-- PENCARIAN DAN FILTER --}}
            <form method="GET"
                  action="{{ route('petugas.fasilitas.index') }}"
                  class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4
                         gap-3 mb-6">

                {{-- PENCARIAN --}}
                <div>
                    <label for="search"
                           class="block text-xs font-semibold
                                  text-gray-600 mb-2">
                        Cari Fasilitas
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Masukkan nama fasilitas"
                        class="w-full px-4 py-2.5 border border-gray-300
                               rounded-lg text-sm outline-none
                               focus:ring-2 focus:ring-blue-500
                               focus:border-blue-500"
                    >
                </div>


                {{-- FILTER TIPE --}}
                <div>
                    <label for="filter_tipe"
                           class="block text-xs font-semibold
                                  text-gray-600 mb-2">
                        Tipe Fasilitas
                    </label>

                    <select
                        name="filter_tipe"
                        id="filter_tipe"
                        class="w-full px-4 py-2.5 border border-gray-300
                               rounded-lg text-sm bg-white outline-none
                               focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Tipe</option>

                        @foreach ($tipeOptions as $tipe)
                            <option
                                value="{{ $tipe }}"
                                {{ $filterTipe == $tipe ? 'selected' : '' }}
                            >
                                {{ ucfirst($tipe) }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- FILTER LOKASI --}}
                <div>
                    <label for="filter_lokasi"
                           class="block text-xs font-semibold
                                  text-gray-600 mb-2">
                        Lokasi
                    </label>

                    <select
                        name="filter_lokasi"
                        id="filter_lokasi"
                        class="w-full px-4 py-2.5 border border-gray-300
                               rounded-lg text-sm bg-white outline-none
                               focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">Semua Lokasi</option>

                        @foreach ($lokasiOptions as $lokasi)
                            <option
                                value="{{ $lokasi }}"
                                {{ $filterLokasi == $lokasi ? 'selected' : '' }}
                            >
                                {{ $lokasi }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- TOMBOL --}}
                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="flex-1 bg-[#1A2254] hover:bg-blue-900
                               text-white px-4 py-2.5 rounded-lg
                               text-sm font-medium transition-colors"
                    >
                        Cari
                    </button>

                    @if ($search || $filterTipe || $filterLokasi)
                    <a href="{{ url()->current() }}" 
                        class="px-5 py-2 border border-red-200 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors flex items-center">
                            Reset Filter
                        </a>
                    @endif
                </div>

            </form>


            {{-- INFORMASI HASIL --}}
            <div class="flex flex-col sm:flex-row sm:justify-between
                        sm:items-center gap-2 mb-4">

                <p class="text-sm text-gray-500">
                    Menampilkan
                    <span class="font-semibold text-gray-800">
                        {{ $fasilitas->count() }}
                    </span>
                    dari
                    <span class="font-semibold text-gray-800">
                        {{ $fasilitas->total() }}
                    </span>
                    fasilitas pada halaman ini.
                </p>

                @if ($search || $filterTipe || $filterLokasi)
                    <p class="text-xs text-blue-700">
                        Filter sedang diterapkan
                    </p>
                @endif

            </div>


            {{-- TABEL --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">

                    <thead>
                        <tr class="bg-gray-100 text-gray-600">
                            <th class="p-4 font-semibold">No.</th>
                            <th class="p-4 font-semibold">Nama Fasilitas</th>
                            <th class="p-4 font-semibold">Tipe</th>
                            <th class="p-4 font-semibold">Lokasi</th>
                            <th class="p-4 font-semibold text-center">
                                Kapasitas
                            </th>
                            <th class="p-4 font-semibold text-center">
                                Status
                            </th>
                            <th class="p-4 font-semibold text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($fasilitas as $item)
                            <tr class="hover:bg-gray-50 transition-colors">

                                <td class="p-4 text-gray-500">
                                    {{ $fasilitas->firstItem() + $loop->index }}
                                </td>

                                <td class="p-4">
                                    <div class="font-semibold text-gray-900">
                                        {{ $item->nama }}
                                    </div>
                                </td>

                                <td class="p-4 text-gray-600">
                                    {{ ucfirst($item->tipe_fasilitas) }}
                                </td>

                                <td class="p-4 text-gray-600">
                                    {{ $item->lokasi }}
                                </td>

                                <td class="p-4 text-center text-gray-700">
                                    {{ $item->kapasitas }}
                                </td>

                                <td class="p-4 text-center">
                                    @php
                                        $status = strtolower(trim($item->status));

                                        $badgeColor = match($status) {
                                            'aktif' => 'bg-green-100 text-green-700',
                                            'dalam_perbaikan'   => 'bg-red-100 text-red-700',
                                            'nonaktif' => 'bg-gray-100 text-gray-700',
                                            default     => 'bg-orange-100 text-orange-600',
                                        };
                                    @endphp
                                    <span class="{{ $badgeColor }} px-4 py-1.5 rounded text-xs font-semibold uppercase">
                                        {{ $status }}
                                    </span>
                                </td>

                                <td class="p-4 align-middle text-center">
                                    <a href="{{ route('petugas.fasilitas.editStatus', $item->id) }}" class="px-4 py-2 text-xs border border-gray-300 text-gray-700 rounded hover:bg-gray-100 font-medium inline-block">
                                        Ubah status
                                    </a>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="7"
                                    class="p-10 text-center">

                                    <div class="flex flex-col items-center">
                                        <p class="text-gray-700 font-semibold">
                                            Fasilitas tidak ditemukan
                                        </p>

                                        <p class="text-gray-500 text-sm mt-1">
                                            Coba ubah kata kunci pencarian
                                            atau reset filter.
                                        </p>

                                        <a
                                            href="{{ route('petugas.fasilitas.index') }}"
                                            class="mt-4 text-sm text-blue-700
                                                   hover:underline"
                                        >
                                            Tampilkan semua fasilitas
                                        </a>
                                    </div>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>
            </div>


            {{-- PAGINATION --}}
            @if ($fasilitas->hasPages())
                <div class="mt-6 pt-5 border-t border-gray-100">
                    {{ $fasilitas->links() }}
                </div>
            @endif

        </div>


        {{-- FOOTER --}}
        <div class="mt-6 text-xs text-gray-400 text-center">
            KARSA &copy; {{ date('Y') }} — Sistem Pengelolaan Fasilitas
        </div>

    </main>

</body>
</html>