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
            <a href="{{ route('fasilitas-public.index') }}">Daftar Fasilitas</a>
            <a href="#prosedur">Panduan</a>
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
            <a href="{{ route('fasilitas-public.index') }}">Cek Fasilitas</a>
        </div>
      </section>

      <!-- Fasilitas Section -->
       <section class="fasilitas" id="fasilitas">
            <h2>Fasilitas</h2>
            <div class="fasilitas-container">
                <div class="fasilitas-card">
                    <img src="{{ asset('img/ruang-kelas.png') }}" alt="Ruang Kelas">

                    <div class="fasilitas-content">
                        <h3>Ruang Kelas</h3>
                        <p>Tersedia</p>
                        <p>50</p>
                    </div>
                </div>

                <div class="fasilitas-card">
                    <img src="{{ asset('img/aula.png') }}" alt="Ruang Kelas">

                    <div class="fasilitas-content">
                        <h3>Aula</h3>
                        <p>Tersedia</p>
                        <p>50</p>
                    </div>
                </div>
                <div class="fasilitas-card">
                    <img src="{{ asset('img/lapangan.png') }}" alt="Ruang Kelas">

                    <div class="fasilitas-content">
                        <h3>Lapangan</h3>
                        <p>Tersedia</p>
                        <p>50</p>
                    </div>
                </div>

                <div class="fasilitas-card">
                    <img src="{{ asset('img/alat.png') }}" alt="Ruang Kelas">

                    <div class="fasilitas-content">
                        <h3>Alat</h3>
                        <p>Tersedia</p>
                        <p>50</p>
                    </div>
                </div>

                <div class="fasilitas-card">
                    <img src="{{ asset('img/laboratorium.png') }}" alt="Ruang Kelas">

                    <div class="fasilitas-content">
                        <h3>Laboratorium</h3>
                        <p>Tersedia</p>
                        <p>50</p>
                    </div>
                </div>
            </div>
       </section>   

        <!-- Prosedur Section -->
       <section class="prosedur" id="prosedur">
        <div class="prosedur-title">
            <h2>Prosedur Peminjaman Fasilitas</h2>
            <h3>Alur Reservasi</h3>
        </div>

        <div class="prosedur-container">
            <div class="prosedur-card">
                <p class="number">1</p>
                <div class="prosedur-content">
                    <h3>Cek Jadwal</h3>
                    <p>Eksplorasi ketersediaan ruang secara publik tanpa perlu login. Pastikan jam tidak tumpang tindih antara 07.00 - 20.00 WIB.</p>
                    <hr>
                </div>
            </div>
            <div class="prosedur-card">
                <p class="number">2</p>
                <div class="prosedur-content">
                    <h3>Ajukan Reservasi</h3>
                    <p>Masuk halaman Reservasi, lengkapi formulir proposal acara, estimasi peserta, serta permohonan alat multimedia.</p>
                    <hr>
                </div>
            </div>
            <div class="prosedur-card">
                <p class="number">3</p>
                <div class="prosedur-content">
                    <h3>Verifikasi Petugas</h3>
                    <p>Petugas memeriksa surat Permohonan, dan proposal dari pengguna.</p>
                    <hr>
                </div>
            </div>
            <div class="prosedur-card">
                <p class="number">4</p>
                <div class="prosedur-content">
                    <h3>Pakai & Lapor</h3>
                    <p>Riwayat Peminjaman di terima oleh petugas dan dapat melaporkan Kerusakan kepada petugas.</p>
                    <hr>
                </div>
            </div>
        </div>
       </section>

     
     <!-- Feather Icon -->
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
      feather.replace();
    </script>
</body>
</html>