<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Petugas</title>
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
                <li><a href="{{ route('petugas.home') }}" class="active">Home</a></li>
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

            <!-- ===== Ringkasan / statistik (sementara) ===== -->
            <div class="card">
                <div class="card-header">
                    <h2>Ringkasan</h2>
                </div>
                <p class="empty-note">
                    Statistik lain (aktivitas, reservasi, laporan, dsb.) akan ditambahkan di sini menyusul.
                </p>
            </div>
        </main>
    </div>
</body>
</html>