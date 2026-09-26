<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman - KARSA</title>
    <link rel="stylesheet" href="{{ asset('css/reservations/riwayat.css') }}">
</head>
<body>

    <!-- ===== Navbar ===== -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-logo">
                <img src="{{ asset('img/logo-undip.png') }}" alt="Logo Undip">
            </a>
            <div class="navbar-title">
                <h3>KARSA</h3>
                <p>Kelola Alat, Ruangan, dan Sarana Akademik.</p>
            </div>
        </div>

        <div class="navbar-nav">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="#">Laporan Kerusakan</a>
            <a href="{{ route('fasilitas-public.index') }}">Daftar Fasilitas</a>
            <a href="{{ route('reservations.riwayat') }}" class="active">Riwayat</a>
            <!-- isi nanti -->
            <a href="" id="profile-icon" aria-label="Profil Saya">
                <i data-feather="user"></i>
            </a>
            <a href="" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>

    <main class="page-content">

        
        <!-- ===== Riwayat Peminjaman ===== -->
        <section class="riwayat-section">
            <div class="section-card">
                <h1>Riwayat Peminjaman</h1>

                <div class="stat-grid">
                    <div class="stat-card">
                        <span class="stat-label">Total Peminjaman</span>
                        <div class="stat-row">
                            <span class="stat-value">{{ $peminjamanStats['total'] ?? 0 }}</span>
                            <span class="stat-pill">Fasilitas</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <span class="stat-label">Sedang Berjalan</span>
                        <div class="stat-row">
                            <span class="stat-value">{{ $peminjamanStats['berjalan'] ?? 0 }}</span>
                            <span class="stat-pill-pending">Fasilitas</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <span class="stat-label">Selesai</span>
                        <div class="stat-row">
                            <span class="stat-value">{{ $peminjamanStats['selesai'] ?? 0 }}</span>
                            <span class="stat-pill-complete">Fasilitas</span>
                        </div>
                    </div>

                    <div class="stat-card">
                        <span class="stat-label">Dibatalkan</span>
                        <div class="stat-row">
                            <span class="stat-value">{{ $peminjamanStats['dibatalkan'] ?? 0 }}</span>
                            <span class="stat-pill stat-pill-danger">Fasilitas</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-card">

                <!-- Filter -->
                <form method="GET" action="{{ route('reservations.riwayat') }}" class="riwayat-filter-form">
                    <div class="filter-search-wrapper">
                        <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/><path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        <input
                            type="text"
                            name="search_peminjaman"
                            value="{{ request('search_peminjaman') }}"
                            placeholder="Cari peminjaman..."
                        >
                    </div>

                    <select name="filter_jenis_peminjaman">
                        <option value="">Jenis Fasilitas</option>
                        @foreach ($tipeOptions ?? [] as $tipe)
                            <option value="{{ $tipe }}" {{ request('filter_jenis_peminjaman') == $tipe ? 'selected' : '' }}>{{ $tipe }}</option>
                        @endforeach
                    </select>

                    <select name="filter_status_peminjaman">
                        <option value="">Status</option>
                        <option value="diproses" {{ request('filter_status_peminjaman') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="disetujui" {{ request('filter_status_peminjaman') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="selesai" {{ request('filter_status_peminjaman') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditolak" {{ request('filter_status_peminjaman') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="dibatalkan" {{ request('filter_status_peminjaman') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>

                    <div class="filter-date-wrapper">
                        <svg class="calendar-icon" width="16" height="16" viewBox="0 0 24 24" fill="none"><rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M3 9H21" stroke="currentColor" stroke-width="1.6"/><path d="M8 3V6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/><path d="M16 3V6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        <input type="date" name="tanggal_peminjaman" value="{{ request('tanggal_peminjaman') }}">
                    </div>

                    <button type="submit" class="btn-filter">Filter</button>
                </form>

                <!-- Tabel Peminjaman -->
                <div class="riwayat-table-wrapper">
                    <table class="riwayat-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Fasilitas</th>
                                <th>Tanggal</th>
                                <th>Waktu</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($reservations as $item)
                                @php
                                    $statusClass = match ($item->status) {
                                        'diproses'   => 'badge-diproses',
                                        'disetujui'  => 'badge-disetujui',
                                        'selesai'    => 'badge-selesai',
                                        'ditolak'    => 'badge-ditolak',
                                        'dibatalkan' => 'badge-ditolak',
                                        default      => 'badge-diproses',
                                    };
                                    $bisaDibatalkan = in_array($item->status, ['diproses', 'disetujui']);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration + ($reservations->currentPage() - 1) * $reservations->perPage() }}</td>
                                    <td class="col-fasilitas">{{ $item->fasilitas->nama ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d - m -Y') }}</td>
                                    <td>{{ $item->start_time }} - {{ $item->end_time }}</td>
                                    <td class="col-deskripsi">{{ Str::limit($item->deskripsi_kegiatan, 40) }}</td>
                                    <td>
                                        <span class="status-badge {{ $statusClass }}">
                                            @if (in_array($item->status, ['ditolak', 'dibatalkan']))<span class="status-dot"></span>@endif
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="col-aksi">
                                        <a href="{{ route('reservations.show', $item->id) }}" class="btn-outline-sm">Lihat Detail</a>
                                        <!-- fitur tambah nanti -->
                                        @if ($bisaDibatalkan)
                                            <form action="{{ route('reservations.cancel', $item->id) }}" method="POST" class="inline-form"
                                                  onsubmit="return confirm('Batalkan peminjaman ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn-danger-sm">Batalkan</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="empty-note">Belum ada riwayat peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($reservations->hasPages())
                    <div class="riwayat-pagination">
                        @if ($reservations->onFirstPage())
                            <span class="disabled">&laquo; Prev</span>
                        @else
                            <a href="{{ $reservations->previousPageUrl() }}">&laquo; Prev</a>
                        @endif

                        @for ($page = 1; $page <= $reservations->lastPage(); $page++)
                            @if ($page == $reservations->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $reservations->url($page) }}">{{ $page }}</a>
                            @endif
                        @endfor

                        @if ($reservations->hasMorePages())
                            <a href="{{ $reservations->nextPageUrl() }}">Next &raquo;</a>
                        @else
                            <span class="disabled">Next &raquo;</span>
                        @endif
                    </div>
                @endif
            </div>
        </section>

    </main>

    <!-- ===== Footer ===== -->
    <footer class="site-footer">
        <div class="footer-grid">
            <div class="footer-col footer-brand">
                <h4>KARSA</h4>
                <p>Platform Manajemen Fasilitas &amp; Reservasi Kampus Terpadu. Mewujudkan tata kelola sarana perguruan tinggi yang transparan, akuntabel, dan berbasis digital demi menunjang Tri Dharma Perguruan Tinggi.</p>
                <p class="footer-note">Project PPK 2026 &bull; Direktorat Sarana &amp; Prasarana Kampus</p>
            </div>

            <div class="footer-col">
                <h5>LAYANAN UTAMA</h5>
                <ul>
                    <li><a href="#">Eksplorasi Fasilitas</a></li>
                    <li><a href="#">Jadwal Publik Kampus</a></li>
                    <li><a href="#">Pengajuan Reservasi</a></li>
                    <li><a href="#">Pelaporan Kerusakan</a></li>
                    <li><a href="#">Tracking Tiket Perbaikan</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>REGULASI KAMPUS</h5>
                <ul>
                    <li><a href="#">SK Rektor No. 42/2026</a></li>
                    <li><a href="#">SOP Jam Malam &amp; Keamanan</a></li>
                    <li><a href="#">Pedoman Penggunaan Laboratorium</a></li>
                    <li><a href="#">Tarif Kebersihan Organisasi Luar</a></li>
                    <li><a href="#">Pencegahan Konflik Jadwal</a></li>
                </ul>
            </div>

            <div class="footer-col">
                <h5>BIRO FASILITAS &amp; ASET</h5>
                <p>Gedung Rektorat Sayap Timur Lt. 1<br>Jl. Kampus Terpadu No. 1, Graha Wiyata</p>
                <p>Hotline: (021) 789-2026<br>Email: sarpras@kampus.ac.id</p>
                <p>Senin - Jumat (07.30 - 16.30 WIB)</p>
            </div>
        </div>

        <div class="footer-bottom">
            <span>&copy; 2026 KARSA Campus Operations. Seluruh Hak Cipta Dilindungi Undang-Undang.</span>
            <div class="footer-links">
                <a href="#">Kebijakan Privasi Data</a>
                <a href="#">Syarat &amp; Ketentuan</a>
                <a href="#">Bantuan Teknis</a>
            </div>
        </div>
    </footer>

    <!-- Feather Icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        feather.replace();
    </script>
</body>
</html>