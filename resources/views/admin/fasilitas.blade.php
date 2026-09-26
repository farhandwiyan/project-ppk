<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fasilitas - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/fasilitas.css') }}">
</head>
<body>
    <div class="layout">

        <!-- ===== Sidebar ===== -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span>KARSA</span>
            </div>

            <ul class="sidebar-nav">
                <li><a href="{{ route('admin.home') }}">Home</a></li>
                <li><a href="{{ route('users.index') }}">Semua User</a></li>
                <li><a href="{{ route('fasilitas.index') }}" class="active">Fasilitas</a></li>
            </ul>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </aside>

        <!-- ===== Main content ===== -->
        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Kelola Fasilitas</h1>
                    <p class="subtitle">Daftar seluruh fasilitas kampus yang terdaftar di sistem KARSA</p>
                </div>
                <a href="{{ route('fasilitas.create') }}" class="btn">+ Tambah Fasilitas</a>
            </div>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            <!-- ===== Search & Filter ===== -->
            <form method="GET" action="{{ route('fasilitas.index') }}" class="fasilitas-filter-form">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nama fasilitas..."
                    class="filter-input filter-search"
                >

                <select name="filter_tipe" class="filter-input">
                    <option value="">Semua Tipe</option>
                    @foreach ($tipeOptions as $tipe)
                        <option value="{{ $tipe }}" @selected($filterTipe == $tipe)>
                            {{ $tipe }}
                        </option>
                    @endforeach
                </select>

                <select name="filter_lokasi" class="filter-input">
                    <option value="">Semua Lokasi</option>
                    @foreach ($lokasiOptions as $lokasi)
                        <option value="{{ $lokasi }}" @selected($filterLokasi == $lokasi)>
                            {{ $lokasi }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn">Terapkan</button>
            </form>

            <!-- ===== Tabel Fasilitas ===== -->
            <div class="card">
                <div class="card-header">
                    <h2>Daftar Fasilitas</h2>
                </div>

                <div class="fasilitas-table-wrapper">
                    <table class="fasilitas-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Tipe</th>
                                <th>Lokasi</th>
                                <th>Kapasitas</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fasilitas as $item)
                                <tr>
                                    <td>{{ $loop->iteration}}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->tipe_fasilitas }}</td>
                                    <td>{{ $item->lokasi }}</td>
                                    <td>{{ $item->kapasitas }} orang</td>
                                    <td class="fasilitas-actions">
                                        <a href="{{ route('fasilitas.edit', $item->id) }}" class="btn-icon">Edit</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-note">Tidak ada fasilitas yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    {{-- Tombol Previous --}}
                    @if ($fasilitas->onFirstPage())
                        <span class="disabled">&laquo; Prev</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}">&laquo; Prev</a>
                    @endif

                    {{-- Nomor halaman --}}
                    @for ($page = 1; $page <= $fasilitas->lastPage(); $page++)
                        @if ($page == $fasilitas->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $users->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    {{-- Tombol Next --}}
                    @if ($fasilitas->hasMorePages())
                        <a href="{{ $users->nextPageUrl() }}">Next &raquo;</a>
                    @else
                        <span class="disabled">Next &raquo;</span>
                    @endif
                </div>
            </div>
        </main>
    </div>
</body>
</html>