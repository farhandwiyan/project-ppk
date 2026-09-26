<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Fasilitas - KARSA</title>
    <link rel="stylesheet" href="{{ asset('css/public/fasilitas-public.css') }}">
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-logo">
                <img src="{{ asset('img/logo-undip.png') }}" alt="Logo Undip">
            </a>
            <div class="navbar-title">
                <h3>KARSA</h3>
                <p>Kelola Alat, Ruangan, dan Sarana Akademik</p>
            </div>
        </div>

        <div class="navbar-nav">
            <a href="{{ route('home') }}">Beranda</a>
            <a href="#">Daftar Fasilitas</a>
            <a href="{{ route('home') }}#prosedur">Panduan</a>
            <a id="button-login" href="{{ route('login') }}">Login</a>
            <a href="" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>

    <!-- ===== Hero ===== -->
    <section class="hero">
        <div class="hero-content">
            <h1>Peminjaman Fasilitas</h1>
        </div>
    </section>

    <main class="page-content">

        <!-- ===== Search & Filter ===== -->
        <form method="GET" action="{{ route('fasilitas-public.index') }}" class="public-filter-form">
            <div class="filter-search-wrapper">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Nama Fasilitas"
                    class="filter-search"
                >
                <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="2"/>
                    <path d="M21 21L16.65 16.65" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>

            <select name="filter_lokasi" class="filter-select" onchange="this.form.submit()">
                <option value="">Lokasi</option>
                @foreach ($lokasiOptions as $lokasi)
                    <option value="{{ $lokasi }}" {{ request('filter_lokasi') == $lokasi ? 'selected' : '' }}>
                        {{ $lokasi }}
                    </option>
                @endforeach
            </select>

            <select name="filter_tipe" class="filter-select" onchange="this.form.submit()">
                <option value="">Tipe Fasilitas</option>
                @foreach ($tipeOptions as $tipe)
                    <option value="{{ $tipe }}" {{ request('filter_tipe') == $tipe ? 'selected' : '' }}>
                        {{ $tipe }}
                    </option>
                @endforeach
            </select>

            <div class="date-picker">
                <input 
                    type="date" 
                    name="tanggal" 
                    id="tanggalInput" 
                    value="{{ $tanggalTerpilih->format('Y-m-d') }}"
                    class="border rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    onchange="this.form.submit()"
                    required
                >
            </div>
        </form>

        <!-- ===== Grid Fasilitas ===== -->
        <div class="fasilitas-grid">
            @forelse ($fasilitas as $item)
                @php
                    $gambar = match (true) {
                        str_contains(strtolower($item->tipe_fasilitas), 'ruangan')  => asset('img/ruang-kelas.png'),
                        str_contains(strtolower($item->tipe_fasilitas), 'aula')     => asset('img/aula.png'),
                        str_contains(strtolower($item->tipe_fasilitas), 'lapangan') => asset('img/lapangan.png'),
                        str_contains(strtolower($item->tipe_fasilitas), 'laboratorium') => asset('img/laboratorium.png'),
                        str_contains(strtolower($item->tipe_fasilitas), 'alat')     => asset('img/alat.png'),
                        default => asset('img/ruang-kelas.png'),
                    };

                    // Status → kelas badge. Sesuaikan dengan sumber data status Anda yang sebenarnya.
                    $statusClass = match ($item->status ?? 'tersedia') {
                        'tersedia'         => 'badge-status-tersedia',
                        'terpakai'         => 'badge-status-terpakai',
                        'dalam_perbaikan'  => 'badge-status-perbaikan',
                        default            => 'badge-status-tersedia',
                    };

                    $statusLabel = match ($item->status ?? 'tersedia') {
                        'tersedia'         => 'Tersedia',
                        'terpakai'         => 'Terpakai',
                        'dalam_perbaikan'  => 'Dalam Perbaikan',
                        default            => 'tersedia',
                    };
                @endphp

                <div class="fasilitas-card">
                    <div class="card-image" style="background-image: url('{{ $gambar }}');">
                        <span class="badge-status {{ $statusClass }}">
                            <span class="status-dot"></span> {{ $statusLabel }}
                        </span>
                        <span class="badge-kapasitas">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M17 20V18C17 15.7909 15.2091 14 13 14H7C4.79086 14 3 15.7909 3 18V20" stroke="currentColor" stroke-width="2"/><circle cx="10" cy="7" r="3" stroke="currentColor" stroke-width="2"/></svg>
                            {{ $item->kapasitas }} Orang
                        </span>
                    </div>

                    <div class="card-body">
                        <div class="card-location">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M12 21C12 21 19 15.5 19 10C19 6.13401 15.866 3 12 3C8.13401 3 5 6.13401 5 10C5 15.5 12 21 12 21Z" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="2"/></svg>
                            {{ $item->lokasi }}
                        </div>

                        <h3 class="card-title">{{ $item->nama }}</h3>

                        @if (!empty($item->alat_pendukung))
                            <div class="card-tags">
                                @foreach ($item->alat_pendukung as $alat)
                                    <span class="tag">{{ $alat }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="card-actions">
                            <a  class="btn-outline">Lihat Jadwal &amp; Detail</a>
                            <a href="{{ route('reservations.create', ['fasilitas' => $item->id, 'tanggal' => request('tanggal')]) }}" class="btn-primary">Ajukan Pinjam</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="fasilitas-empty">
                    Tidak ada fasilitas yang cocok dengan pencarian Anda.
                </div>
            @endforelse
        </div>

                <div class="public-pagination">
            @if ($fasilitas->hasPages())
                @if ($fasilitas->onFirstPage())
                    <span class="disabled">&laquo; Prev</span>
                @else
                    <a href="{{ $fasilitas->previousPageUrl() }}">&laquo; Prev</a>
                @endif
 
                @for ($page = 1; $page <= $fasilitas->lastPage(); $page++)
                    @if ($page == $fasilitas->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $fasilitas->url($page) }}">{{ $page }}</a>
                    @endif
                @endfor
 
                @if ($fasilitas->hasMorePages())
                    <a href="{{ $fasilitas->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span class="disabled">Next &raquo;</span>
                @endif
            @endif
        </div>


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

    <script>
        // Navigasi tanggal (H-1 / H+1) tanpa reload halaman penuh — submit ulang form dengan tanggal baru
        document.querySelectorAll('.date-nav').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const input = document.getElementById('tanggalInput');
                const direction = parseInt(this.dataset.direction, 10);
                const current = new Date(input.value);
                current.setDate(current.getDate() + direction);

                const yyyy = current.getFullYear();
                const mm = String(current.getMonth() + 1).padStart(2, '0');
                const dd = String(current.getDate()).padStart(2, '0');
                input.value = `${yyyy}-${mm}-${dd}`;

                input.closest('form').submit();
            });
        });
    </script>
</body>
</html>