<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Fasilitas - KARSA</title>
    <link rel="stylesheet" href="{{ asset('css/public/fasilitas-public.css') }}">
    <!-- Load Tailwind CSS & Feather Icons -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body class="bg-gray-50">

    <!-- Navbar (Disamakan dengan fasilitas-public) -->
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
            <a href="{{ route('fasilitas-public.index') }}" class="active">Daftar Fasilitas</a>

    <!-- Tampil jika pengguna BELUM login -->
        @guest
            <a id="button-login" href="{{ route('login') }}">Login</a>
        @endguest

        <!-- Tampil jika pengguna SUDAH login -->
        @auth
            <a href="{{ route('laporan.create') }}">Laporan Kerusakan</a> 
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

  

    <!-- KONTEN UTAMA -->
        @php
            $gambar = match (true) {
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'ruangan')  => asset('img/ruang-kelas.png'),
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'aula')     => asset('img/aula.png'),
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'lapangan') => asset('img/lapangan.png'),
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'laboratorium') => asset('img/laboratorium.png'),
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'alat')     => asset('img/alat.png'),
                default => asset('img/ruang-kelas.png'),
            };
        @endphp
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 font-sans text-gray-800 mt-24">        
            
        <!-- 2. Header & Tombol Aksi -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-6">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-2 py-1 rounded">TERSEDIA HARI INI</span>
                    <span class="text-gray-500 text-sm">Kategori: {{ $fasilitas->tipe_fasilitas }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $fasilitas->nama }}</h1>
                <p class="text-gray-600 text-sm mb-1">
                    <i data-feather="map-pin" class="inline w-4 h-4"></i> {{ $fasilitas->lokasi }}
                </p>
                <p class="text-gray-600 text-sm font-medium">
                    <i data-feather="users" class="inline w-4 h-4"></i> Kapasitas Maksimal: {{ $fasilitas->kapasitas }} Orang
                </p>
            </div>
            <div class="flex flex-col gap-3 mt-4 lg:mt-0">
                
                <a href="{{ route('laporan.create', ['fasilitas' => $fasilitas->id]) }}" class="bg-white text-red-600 border border-red-200 px-6 py-2.5 rounded-lg text-sm font-semibold text-center flex items-center justify-center gap-2 hover:bg-red-50 transition">
                    <i data-feather="alert-triangle" class="w-4 h-4"></i> Lapor Kerusakan Ruang Ini
                </a>
            </div>
        </div>

        <!-- 3. Galeri Foto (Disesuaikan dengan format database & form reservasi) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="md:col-span-2 bg-gray-200 rounded-xl h-64 md:h-96 overflow-hidden flex items-center justify-center text-gray-400 relative shadow-sm">
                @if(!empty($gambar))
                    <img src="{{ $gambar }}" alt="{{ $fasilitas->nama }}" class="w-full h-full object-cover">
                @else
                    <div class="text-center p-6">
                        <i data-feather="image" class="w-12 h-12 mx-auto mb-2 text-gray-400"></i>
                        <span class="text-sm">Foto Utama Fasilitas</span>
                    </div>
                @endif
            </div>
            
            <div class="flex flex-col gap-4 h-64 md:h-96">
                <div class="bg-gray-200 rounded-xl h-1/2 overflow-hidden flex items-center justify-center text-gray-400 text-sm shadow-sm relative">
                    @if(!empty($gambar))
                        <img src="{{ $gambar }}" alt="{{ $fasilitas->nama }}" class="w-full h-full object-cover">
                    @else
                        <span class="flex items-center gap-1"><i data-feather="image" class="w-4 h-4"></i> Foto Pendukung 1</span>
                    @endif
                </div>
                <div class="bg-gray-200 rounded-xl h-1/2 overflow-hidden relative flex items-center justify-center text-gray-400 text-sm shadow-sm">
                    @if(!empty($gambar))
                        <img src="{{ $gambar }}" alt="{{ $fasilitas->nama }}" class="w-full h-full object-cover">
                    @else
                        <span class="flex items-center gap-1"><i data-feather="image" class="w-4 h-4"></i> Foto Pendukung 2</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- 4. Info Kapasitas & Jam Operasional -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div class="bg-white border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-sm">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Kapasitas Kursi</p>
                    <p class="text-xl font-bold">{{ $fasilitas->kapasitas }} Kursi</p>
                </div>
                <i data-feather="users" class="text-gray-400"></i>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-sm">
                <div>
                    <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Jam Operasional</p>
                    <p class="text-xl font-bold">07:00 - 20:00</p>
                </div>
                <i data-feather="clock" class="text-gray-400"></i>
            </div>
        </div>


            <!-- Filter Tanggal -->
            <form method="GET" action="{{ route('fasilitas.show', $fasilitas->id) }}" class="bg-gray-50 rounded-lg p-4 mb-6 border border-gray-100">
                <label class="block text-sm font-semibold mb-2">Pilih Tanggal Kegiatan *</label>
                <div class="flex gap-4">
                    <input type="date" name="tanggal" value="{{ $tanggalPilih }}" onchange="this.form.submit()" class="border border-gray-300 rounded-lg px-3 py-2 w-full md:w-64 focus:ring-black focus:border-black">
                </div>
                
                <div class="flex flex-wrap gap-4 mt-6 text-xs text-gray-600">
                    <span class="font-semibold text-gray-800 mr-2">KETERANGAN SLOT:</span>
                    <span class="flex items-center gap-1"><div class="w-3 h-3 border-2 border-green-600 rounded-sm"></div> Tersedia</span>
                    <span class="flex items-center gap-1"><div class="w-3 h-3 bg-gray-200 rounded-sm"></div> Terisi</span>
                    <span class="flex items-center gap-1"><div class="w-3 h-3 bg-yellow-100 rounded-sm"></div> Menunggu</span>
                </div>
            </form>

            <!-- Grid Slot Waktu -->
            <div class="mb-4">
                <h3 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">Jam Oprasional (07:00 - 20:00 WIB)</h3>

                @php
                    $waktuMulai = \Carbon\Carbon::createFromFormat('H:i', '07:00');
                    $waktuSelesai = \Carbon\Carbon::createFromFormat('H:i', '20:00');
                    $slots = [];

                    while ($waktuMulai < $waktuSelesai) {
                        $start = $waktuMulai->format('H:i');
                        $waktuMulai->addMinutes(30);
                        $end = $waktuMulai->format('H:i');
                        $slots[] = ['start' => $start, 'end' => $end];
                    }
                @endphp



                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach ($slots as $slot)
                        @php
                            // Default slot kosong (Hijau)
                            $statusSlot  = 'tersedia';
                            $labelSlot   = 'Tersedia';
                            $warnaBg     = 'bg-white';
                            $warnaBorder = 'border-green-600';
                            $warnaTeks   = 'text-green-700';
                            $warnaDot    = 'bg-green-600';

                            // Loop data reservasi dari database
                            // Cek apakah slot waktu ini bertabrakan dengan data reservasi di database
                            foreach ($reservasiHariIni as $res) {
                                // MENGGUNAKAN NAMA KOLOM ASLI DARI MIGRATION (start_time dan end_time)
                                $waktuMulaiDb = $res->start_time; 
                                $waktuSelesaiDb = $res->end_time;

                                // Pastikan data jam tidak kosong sebelum diolah
                                if ($waktuMulaiDb && $waktuSelesaiDb) {
                                    $resStart = \Carbon\Carbon::parse($waktuMulaiDb)->format('H:i');
                                    $resEnd   = \Carbon\Carbon::parse($waktuSelesaiDb)->format('H:i');

                                    // Cek bentrok jam
                                    if ($slot['start'] >= $resStart && $slot['start'] < $resEnd) {
                                        
                                        // Ubah status menjadi huruf kecil semua (case-insensitive)
                                        $statusReservasi = strtolower($res->status);

                                        if ($statusReservasi == 'disetujui') {
                                            $statusSlot  = 'terisi';
                                            $labelSlot   = 'Terisi';
                                            $warnaBg     = 'bg-gray-100';
                                            $warnaBorder = 'border-gray-300';
                                            $warnaTeks   = 'text-gray-600';
                                            $warnaDot    = 'bg-gray-400';
                                        } elseif ($statusReservasi == 'menunggu') {
                                            $statusSlot  = 'menunggu';
                                            $labelSlot   = 'Menunggu ACC';
                                            $warnaBg     = 'bg-yellow-50';
                                            $warnaBorder = 'border-yellow-300';
                                            $warnaTeks   = 'text-yellow-700';
                                            $warnaDot    = 'bg-yellow-500';
                                        }
                                        break; 
                                    }
                                }
                            }
                        @endphp

                        <div class="{{ $warnaBg }} border-2 {{ $warnaBorder }} rounded p-2 text-xs transition">
                            <div class="flex justify-between items-start">
                                <p class="font-bold text-gray-900">{{ $slot['start'] }} - {{ $slot['end'] }}</p>
                                <div class="w-2 h-2 rounded-full {{ $warnaDot }}"></div>
                            </div>
                            <p class="{{ $warnaTeks }} font-semibold mt-1">{{ $labelSlot }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sticky Bar Bawah -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    <p class="text-sm font-semibold text-gray-900">
                        <i data-feather="check-square" class="inline w-4 h-4 mr-1"></i> 
                        Jadwalkan fasilitas ini sekarang
                    </p>
                    <p class="text-xs text-gray-500 mt-1">Fasilitas: {{ $fasilitas->nama }} • {{ $tanggalFormat ?? $tanggalPilih }}</p>
                </div>
                <a href="{{ route('reservations.create', ['fasilitas' => $fasilitas->id, 'tanggal' => $tanggalPilih]) }}" style="background-color: #1c1d46;" class="text-white px-6 py-2.5 rounded-lg text-sm font-semibold text-center hover:opacity-90 transition">
                    Form Reservasi
                </a>
            </div>
        </div>
    </div>

    <!-- Script render ikon -->
    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>

    <script>
    function toggleProfileMenu() {
        var dropdown = document.getElementById("profileDropdown");
        if (dropdown.style.display === "none" || dropdown.style.display === "") {
            dropdown.style.display = "flex";
        } else {
            dropdown.style.display = "none";
        }
    }

    // Otomatis menutup dropdown jika user mengklik area kosong di luar menu
    window.onclick = function(event) {
        if (!event.target.closest('#profileContainer')) {
            var dropdown = document.getElementById("profileDropdown");
            if (dropdown && dropdown.style.display === "flex") {
                dropdown.style.display = "none";
            }
        }
    }

    </script>
    </script>    
</body>
</html>