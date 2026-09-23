<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Karsa</title>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>
    <!-- Navbar -->
     <nav class="navbar">
        <div class="navbar-container">
            <a href="#" class="navbar-logo">
                <img src="{{ asset('img/logo-undip.png') }}" alt="Logo Undip">
            </a>
            <div class="navbar-title">
                <h3>KARSA</h3>
                <p>Kelola Alat, Ruangan, dan Sarana Akademik</p>
            </div>
        </div>


        <div class="navbar-nav">
            <a href="#home">Beranda</a>
            <a href="#">Laporan Kerusakan</a>
            <a href="#">Panduan</a>
            <a id="button-login" href="{{ route('login') }}">Login</a>
            <a href="" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
     </nav>

     <!-- Hero Section -->
      <section class="hero" id="home">
        <div class="hero-content">
            <h1>Selamat Datang di Karsa</h1>
            <p>Temukan, cek ketersediaan, dan reservasi ruangan serta 
                fasilitas kampus favoritmu hanya dalam beberapa klik.</p>
            <a href="#">Cek Fasilitas</a>
        </div>
      </section>

     
     <!-- Feather Icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
      feather.replace();
    </script>
</body>
</html>