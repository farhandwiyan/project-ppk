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

    <!-- Tampil jika pengguna BELUM login -->
            @guest
                <a id="button-login" href="{{ route('login') }}">Login</a>
            @endguest

    <!-- Tampil jika pengguna SUDAH login -->
        @auth
            <a href="#">Laporan Kerusakan</a> 
            <a href="{{ route('reservations.riwayat') }}">Riwayat</a>
            
            <!-- Tombol Logout -->
            <div id="profileContainer" style="position: relative; display: inline-block; margin-left: 20px;">
            
            <!-- Ikon User Bulat Biru Tua -->
            <button onclick="toggleProfileMenu()" style="display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; background-color: #17183B; border-radius: 50%; border: none; cursor: pointer; padding: 0;">
                <!-- Ukuran SVG dibatasi paksa 24px agar tidak raksasa -->
                <svg style="width: 22px; height: 22px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </button>

            <!-- Menu Dropdown -->
           <div id="profileDropdown" style="display: none; position: absolute; right: 0; top: 55px; width: 220px; box-sizing: border-box; background-color: white; border: 1px solid #eaeaea; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 15px; z-index: 1000; flex-direction: column; gap: 12px;">
                
                <!-- Tombol Edit Profil -->
                <a href="{{ route('update-user') }}" style="display: flex; justify-content: center; align-items: center; width: 100%; box-sizing: border-box; margin: 0; padding: 10px; text-decoration: none; color: black; font-size: 14px; font-weight: 600; border: 2px solid #0EA5E9; border-radius: 8px;">
                    Edit Profil
                </a>

                <!-- Tombol Keluar / Logout -->
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0; width: 100%; box-sizing: border-box;">
                    @csrf
                    <button type="submit" style="display: flex; justify-content: center; align-items: center; gap: 8px; width: 100%; box-sizing: border-box; margin: 0; padding: 10px; background-color: #E11D48; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                        <!-- Ikon Logout -->
                        <svg style="width: 18px; height: 18px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar / Logout
                    </button>
                </form>
                
            </div>
        </div>
        @endauth

    <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
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
    <script>
    function toggleProfileMenu() {
        var menu = document.getElementById('profileDropdown');
        if (menu.style.display === 'none' || menu.style.display === '') {
            menu.style.display = 'flex'; 
        } else {
            menu.style.display = 'none'; 
        }
    }

    window.addEventListener('click', function(e) {
        var container = document.getElementById('profileContainer');
        var menu = document.getElementById('profileDropdown');
        if (container && menu && !container.contains(e.target)) {
            menu.style.display = 'none';
        }
    });
    </script>
</body>
</html>