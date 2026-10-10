<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Admin</title>
    <!-- Memanggil file CSS yang sudah kita update -->
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
</head>
<body>
    <div class="layout">

        <!-- ===== Sidebar ===== -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span>KARSA</span>
            </div>

            <ul class="sidebar-nav">
                <li><a href="{{ route('admin.home') }}" class="active">Home</a></li>
                <li><a href="{{ route('users.index') }}">Semua User</a></li>
                <li><a href="{{ route('fasilitas.index') }}">Fasilitas</a></li>
                
                
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
                    <h1>Halo, {{ auth()->user()->nama }}</h1>
                    <p class="subtitle">Selamat datang kembali di KARSA</p>
                </div>
                <a href="{{ route('update-user') }}" class="btn">Edit Profil</a>
            </div>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            <!-- ===== Ringkasan / statistik ===== -->
            <div class="stats-grid">
                <div class="stat-card">
                    <span class="stat-label">User Terverifikasi</span>
                    <span class="stat-value">{{ $verifiedUsers ?? '-' }}</span>
                    <span class="stat-hint">Status akun sudah diverifikasi</span>
                </div>

                <div class="stat-card">
                    <span class="stat-label">Total Fasilitas</span>
                    <span class="stat-value">{{ $totalFasilitas ?? 0 }}</span>
                    <span class="stat-hint">Fasilitas terdaftar di sistem</span>
                </div>

                <div class="stat-card">
                    <span class="stat-label">Reservasi Bulan Ini</span>
                    <span class="stat-value">{{ $reservasiBulanIni ?? 0 }}</span>
                    <span class="stat-hint">Total reservasi bulan berjalan</span>
                </div>

                <div class="stat-card">
                    <span class="stat-label">Laporan Kerusakan</span>
                    <span class="stat-value">{{ $laporanBulanIni ?? 0 }}</span>
                    <span class="stat-hint">Laporan masuk bulan ini</span>
                </div>
            </div>

            <!-- ===== Pusat Laporan ===== -->
            <div class="card report-card">
                <div class="card-header">
                    <h2>Pusat Laporan & Rekapitulasi</h2>
                </div>
                <div class="card-body report-card-body">
                    <p class="report-description">
                        Pilih jenis laporan yang ingin Anda lihat. Anda dapat melakukan filter berdasarkan periode dan lokasi, serta mengekspor data ke format PDF atau Excel di halaman masing-masing laporan.
                    </p>
                    <a href="{{ route('admin.laporan.okupansi') }}" class="btn-report">Lihat Rekap Okupansi</a>
                    <a href="{{ route('admin.laporan.kerusakan') }}" class="btn-report">Lihat Rekap Kerusakan</a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>