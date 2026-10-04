<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Form Pelaporan - Karsa</title>

    <link rel="stylesheet" href="{{ asset('css/laporan/create.css') }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/laporan/create.css') }}">
</head>

@if (session('success'))
    <div class="success-modal" id="successModal">
        <div class="success-modal-content">
            <h2>
                Laporan Anda Berhasil<br>
                Dikirim!
            </h2>

            <a href="{{ route('home') }}" class="success-button">
                Kembali ke Beranda
            </a>
        </div>
    </div>
@endif

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
<<<<<<< Updated upstream
    <!-- Menu Publik (Selalu Muncul) -->
    <a href="{{ route('home') }}">Beranda</a>
    <a href="{{ route('fasilitas-public.index') }}">Daftar Fasilitas</a>

    <!-- Menu Khusus Pengguna Login -->
    @auth
        <!-- Link kosong untuk form Claudia yang belum jadi -->
        <a href="{{ route('laporan.create') }}" class="active">Laporan Kerusakan</a> 
        <a href="{{ route('reservations.riwayat') }}">Riwayat</a>
    @endauth

    <!-- Tombol Dinamis (Login vs Logout) -->
    @guest
        <a id="button-login" href="{{ route('login') }}">Login</a>
    @endguest

    <!-- Ikon Profil & Dropdown (Untuk User Login) -->
   @auth
        <div id="profileContainer" style="position: relative; display: inline-block; margin-left: 20px;">
            
            <!-- Ikon User Bulat Biru Tua -->
=======
            <a href="{{ route('home') }}">Beranda</a>
            <a href="{{ route('fasilitas-public.index') }}">Daftar Fasilitas</a>
            <a href="{{ route('laporan.create') }}">Laporan Kerusakan</a>
            <a href="{{ route('reservations.riwayat') }}">Riwayat</a>

            @auth
            <div id="profileContainer" style="position: relative; display: inline-block; margin-left: 20px;">

>>>>>>> Stashed changes
            <button onclick="toggleProfileMenu()" style="display: flex; align-items: center; justify-content: center; width: 42px; height: 42px; background-color: #17183B; border-radius: 50%; border: none; cursor: pointer; padding: 0;">
                <!-- Ukuran SVG dibatasi paksa 24px agar tidak raksasa -->
                <svg style="width: 22px; height: 22px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            </button>

            <!-- Menu Dropdown -->
<<<<<<< Updated upstream
            <div id="profileDropdown" style="display: none; position: absolute; right: 0; top: 55px; width: 200px; background-color: white; border: 1px solid #eaeaea; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 15px; z-index: 1000; flex-direction: column; gap: 12px;">
                
                <!-- Tombol Edit Profil dengan border biru -->
=======
           <div id="profileDropdown" style="display: none; position: absolute; right: 0; top: 55px; width: 220px; box-sizing: border-box; background-color: white; border: 1px solid #eaeaea; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); padding: 15px; z-index: 1000; flex-direction: column; gap: 12px;">
                
                <!-- Tombol Edit Profil -->
>>>>>>> Stashed changes
                <a href="{{ route('update-user') }}" style="display: flex; justify-content: center; align-items: center; width: 100%; box-sizing: border-box; margin: 0; padding: 10px; text-decoration: none; color: black; font-size: 14px; font-weight: 600; border: 2px solid #0EA5E9; border-radius: 8px;">
                    Edit Profil
                </a>

<<<<<<< Updated upstream
                <!-- Tombol Keluar / Logout merah -->
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0; width: 100%;">
                    @csrf
                    <button type="submit" style="display: flex; justify-content: center; align-items: center; gap: 8px; width: 100%; padding: 10px; background-color: #E11D48; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; box-sizing: border-box;">
                        <!-- Ikon Logout kecil -->
=======
                <!-- Tombol Keluar / Logout -->
                <form action="{{ route('logout') }}" method="POST" style="margin: 0; padding: 0; width: 100%; box-sizing: border-box;">
                    @csrf
                    <button type="submit" style="display: flex; justify-content: center; align-items: center; gap: 8px; width: 100%; box-sizing: border-box; margin: 0; padding: 10px; background-color: #E11D48; color: white; border: none; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer;">
                        <!-- Ikon Logout -->
>>>>>>> Stashed changes
                        <svg style="width: 18px; height: 18px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar / Logout
                    </button>
                </form>
<<<<<<< Updated upstream

            </div>
=======
                
            </div>
            @endauth

            <a href="" id="hamburger-menu"><i data-feather="menu"></i></a>
>>>>>>> Stashed changes
        </div>
    @endauth

    <!-- Menu Hamburger untuk tampilan Mobile -->
    <a href="#" id="hamburger-menu"><i data-feather="menu"></i></a>
</div>
    </nav>


    <!-- Main Content -->
    <main class="main-container">

        <!-- Intro -->
        <section class="intro-card">

            <h1>
                Pusat Pelaporan Kerusakan Fasilitas & Sarpras Kampus
            </h1>

            <p>
                Bantu rawat sarana belajar kita. Laporkan sarana bermasalah,
                AC bocor, proyektor mati, mebel rusak, atau kelistrikan
                dengan bukti foto untuk penanganan cepat teknisi.
            </p>

        </section>


        <!-- Form -->
        <section class="form-card">

            <div class="form-header">
                <h2>Form Pelaporan Kerusakan</h2>

                <p>
                    <span>*</span>
                    Pelaporan Kerusakan Anda akan ditinjau dan dikonfirmasi
                    terlebih dahulu oleh petugas
                </p>
            </div>


            <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf

                <!-- Nama -->
                <div class="form-group">
                    <label for="nama">
                        Nama Pelapor <span>*</span>
                    </label>

                    <input
                        type="text"
                        id="nama_pelapor"
                        name="nama_pelapor"
                        value="{{ old('nama_pelapor') }}"
                        placeholder="Contoh: Max Verstappen"
                        class="@error('nama_pelapor') is-invalid @enderror"
                    >

                    @error('nama_pelapor')
                    <small class="error-message">Nama Pelapor harus diisi.</small>
                    @enderror
                </div>


                <!-- Email -->
                <div class="form-group">
                    <label for="email">
                        E-mail <span>*</span>
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Contoh: MaxVerstappen@gmail.com"
                        class="@error('email') is-invalid @enderror"
                    >

                    @error('email')
                    <small class="error-message">Email harus diisi.</small>
                    @enderror
                </div>


                <!-- Nomor Telepon -->
                <div class="form-group">
                    <label for="telepon">
                        Nomor Telepon <span>*</span>
                    </label>

                    <input
                        type="tel"
                        id="nomor_telepon"
                        name="nomor_telepon"
                        value="{{ old('nomor_telepon') }}"
                        placeholder="Contoh: 08123456789"
                        class="@error('nomor_telepon') is-invalid @enderror"
                    >

                    @error('nomor_telepon')
                    <small class="error-message">Nomor Telepon harus diisi.</small>
                    @enderror
                </div>


                <!-- Lokasi Fasilitas -->
                <div class="form-group">
                    <label for="lokasi_fasilitas">
                        Lokasi Fasilitas <span>*</span>
                    </label>

                    <select id="lokasi" name="lokasi" class="@error('lokasi') is-invalid @enderror">
                        <option value="">Pilih Lokasi Fasilitas</option>
                        @foreach ($fasilitas->unique('lokasi') as $item)
                            <option value="{{ $item->lokasi }}">
                                {{ $item->lokasi }}
                            </option>
                        @endforeach
                    </select>

                    @error('lokasi')
                    <small class="error-message">Lokasi Fasilitas harus diisi.</small>
                    @enderror
                </div>

                <!-- Jenis Fasilitas -->
                <div class="form-group">
                    <label for="jenis_fasilitas">
                        Jenis Fasilitas <span>*</span>
                    </label>

                    <select id="jenis" name="jenis" class="@error('jenis') is-invalid @enderror">
                        <option value="">Pilih Jenis Fasilitas</option>
                    </select>

                    @error('jenis')
                    <small class="error-message">Jenis Fasilitas harus diisi.</small>
                    @enderror
                </div>

                <!-- Nama Fasilitas -->
                <div class="form-group">
                    <label for="fasilitas_id">
                        Nama Fasilitas <span>*</span>
                    </label>

                    <select id="fasilitas_id" name="fasilitas_id" class="@error('fasilitas_id') is-invalid @enderror">
                        <option value="">Pilih Nama Fasilitas</option>
                    </select>

                    @error('fasilitas_id')
                    <small class="error-message">Nama Fasilitas harus diisi.</small>
                    @enderror
                </div>


                <!-- Deskripsi -->
                <div class="form-group">
                    <label for="deskripsi">
                        Deskripsi Kerusakan <span>*</span>
                    </label>

                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="5"
                        placeholder="Contoh: AC rusak dan tidak mengeluarkan udara dingin"
                        class="@error('deskripsi') is-invalid @enderror"
                    >{{ old('deskripsi') }}</textarea>

                    @error('deskripsi')
                    <small class="error-message">Deskripsi harus diisi.</small>
                    @enderror
                </div>


                <!-- Upload -->
                <div class="form-group">
                    <label>
                        Foto Bukti Kerusakan <span>*</span>
                    </label>

                    <div class="upload-box">

                        <div class="upload-icon">
                            📷
                        </div>

                        <p class="upload-title">
                            Tarik dokumen ke sini atau
                            <span>Jelajahi Berkas</span>
                        </p>

                        <p class="upload-info">
                            Mendukung format JPEG/JPG/PNG
                            (Maksimal 5 file dengan setiap file maksimal 5 MB)
                        </p>

                        <div id="preview-container" class="preview-container"></div>

                        <div id="image-modal" class="image-modal">
                            <div class="modal-content">
                                <button type="button" id="close-modal" class="close-modal">
                                    &times;
                                </button>
                                <img id="modal-image" src="" alt="Preview foto">
                                <p id="modal-name"></p>
                            </div>
                        </div>

                        <input
                            type="file"
                            id="bukti_kerusakan"
                            name="bukti_kerusakan[]"
                            accept=".jpg,.jpeg,.png"
                            multiple
                            class="@error('bukti_kerusakan') is-invalid @enderror"
                        >
                        @error('bukti_kerusakan')
                        <small class="error-message">{{ $message }}</small>
                        @enderror
                        
                        @error('bukti_kerusakan.*')
                        <small class="error-message">{{ $message }}</small>
                        @enderror
                        
                        <small id="foto-error" class="error-message" style="display: none;">
                        Foto bukti kerusakan harus diunggah.
                        </small>
                    </div>
                </div>


                <!-- Submit -->
                <button type="submit" class="submit-button">
                    Laporkan Kerusakan
                </button>

            </form>

        </section>

    </main>


    <!-- Footer -->
    <footer class="footer">

        <div class="footer-container">

            <div class="footer-column footer-about">
                <h3>KARSA</h3>

                <p>
                    Platform Manajemen Fasilitas & Reservasi Kampus Terpadu.
                    Mewujudkan tata kelola sarana perguruan tinggi yang
                    transparan, akuntabel, dan berbasis digital demi menunjang
                    Tri Dharma Perguruan Tinggi.
                </p>

                <strong>
                    Project PPK 2026 • Direktorat Sarana & Prasarana Kampus
                </strong>
            </div>


            <div class="footer-column">
                <h4>LAYANAN UTAMA</h4>

                <a href="{{ route('fasilitas-public.index') }}">
                    Eksplorasi Fasilitas
                </a>

                <a href="#">
                    Jadwal Publik Kampus
                </a>

                <a href="#">
                    Pengajuan Reservasi
                </a>

                <a href="{{ route('laporan.create') }}">
                    Pelaporan Kerusakan
                </a>

                <a href="#">
                    Tracking Titik Perbaikan
                </a>
            </div>


            <div class="footer-column">
                <h4>REGULASI KAMPUS</h4>

                <a href="#">SK Rektor No. 42/2026</a>
                <a href="#">SOP Jam Malam & Keamanan</a>
                <a href="#">Pedoman Penggunaan Laboratorium</a>
                <a href="#">Tarif Kebersihan Organisasi Luar</a>
                <a href="#">Pencegahan Konflik Jadwal</a>
            </div>


            <div class="footer-column">
                <h4>BIRO FASILITAS & ASET</h4>

                <p>
                    Gedung Rektorat Sayap Timur Lt. 1<br>
                    Jl. Kampus Undip No. 1, Grha Wisata
                </p>

                <p>
                    Hotline: (021) 789-2026<br>
                    Email: sarpras@kampus.ac.id
                </p>

                <p>
                    Senin - Jumat (07.30 - 16.30 WIB)
                </p>
            </div>

        </div>


        <div class="footer-bottom">

            <span>
                © 2026 KARSA Campus Operations.
                Seluruh Hak Cipta Dilindungi Undang-Undang.
            </span>

            <div>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
                <a href="#">Bantuan Teknis</a>
            </div>

        </div>

    </footer>

</body>

</html>

<script>
    const fasilitas = @json($fasilitas);

    const lokasiSelect = document.getElementById('lokasi');
    const jenisSelect = document.getElementById('jenis');
    const fasilitasSelect = document.getElementById('fasilitas_id');

    // Ketika lokasi dipilih
    lokasiSelect.addEventListener('change', function () {

        const lokasiDipilih = this.value;

        // Kosongkan jenis
        jenisSelect.innerHTML =
            '<option value="">Pilih Jenis Fasilitas</option>';

        // Kosongkan nama
        fasilitasSelect.innerHTML =
            '<option value="">Pilih Nama Fasilitas</option>';

        if (lokasiDipilih === '') {
            return;
        }

        // Cari fasilitas yang berada di lokasi yang dipilih
        const fasilitasDiLokasi = fasilitas.filter(function (item) {
            return item.lokasi === lokasiDipilih;
        });

        // Ambil jenis yang unik
        const jenisUnik = [
            ...new Set(
                fasilitasDiLokasi.map(function (item) {
                    return item.tipe_fasilitas;
                })
            )
        ];

        // Masukkan jenis ke dropdown
        jenisUnik.forEach(function (jenis) {

            const option = document.createElement('option');

            option.value = jenis;
            option.textContent = jenis;

            jenisSelect.appendChild(option);
        });
    });


    // Ketika jenis dipilih
    jenisSelect.addEventListener('change', function () {

        const lokasiDipilih = lokasiSelect.value;
        const jenisDipilih = this.value;

        // Kosongkan nama fasilitas
        fasilitasSelect.innerHTML =
            '<option value="">Pilih Nama Fasilitas</option>';

        if (lokasiDipilih === '' || jenisDipilih === '') {
            return;
        }

        // Cari fasilitas yang memenuhi:
        // 1. lokasi sesuai
        // 2. jenis sesuai
        const fasilitasSesuai = fasilitas.filter(function (item) {

            return item.lokasi === lokasiDipilih &&
                   item.tipe_fasilitas === jenisDipilih;

        });

        // Masukkan nama fasilitas hasil filter
        fasilitasSesuai.forEach(function (item) {

            const option = document.createElement('option');

            option.value = item.id;
            option.textContent = item.nama;

            fasilitasSelect.appendChild(option);
        });
    });
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
        var container = document.getElementById('');
        var menu = document.getElementById('profileDropdown');
        if (container && menu && !container.contains(e.target)) {
            menu.style.display = 'none';
        }
    });
</script>

<script>
    const fileInput = document.getElementById('bukti_kerusakan');
    const previewContainer = document.getElementById('preview-container');

    const imageModal = document.getElementById('image-modal');
    const modalImage = document.getElementById('modal-image');
    const modalName = document.getElementById('modal-name');
    const closeModal = document.getElementById('close-modal');

    const maxFiles = 5;
    const maxSize = 5 * 1024 * 1024;
    const allowedTypes = ['image/jpeg', 'image/png'];

    let selectedFiles = [];

    function renderFileList() {
        previewContainer.innerHTML = '';

        selectedFiles.forEach((file, index) => {
            const item = document.createElement('div');
            item.classList.add('file-item');

            const fileName = document.createElement('button');
            fileName.type = 'button';
            fileName.classList.add('file-name');
            fileName.textContent = file.name;

            fileName.addEventListener('click', () => {
                const imageUrl = URL.createObjectURL(file);

                modalImage.src = imageUrl;
                modalName.textContent = file.name;
                imageModal.style.display = 'flex';

                modalImage.onload = () => {
                    URL.revokeObjectURL(imageUrl);
                };
            });

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('remove-file');
            removeButton.textContent = 'Hapus';

            removeButton.addEventListener('click', () => {
                selectedFiles.splice(index, 1);
                updateInputFiles();
                renderFileList();
            });

            item.appendChild(fileName);
            item.appendChild(removeButton);
            previewContainer.appendChild(item);
        });

        previewContainer.style.display =
            selectedFiles.length > 0 ? 'flex' : 'none';
    }

    function updateInputFiles() {
        const dataTransfer = new DataTransfer();

        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });

        fileInput.files = dataTransfer.files;
    }

    fileInput.addEventListener('change', function () {
        const newFiles = Array.from(this.files);

        if (selectedFiles.length + newFiles.length > maxFiles) {
            alert('Maksimal hanya boleh memilih 5 file.');
            updateInputFiles();
            return;
        }

        for (const file of newFiles) {
            if (!allowedTypes.includes(file.type)) {
                alert(`Format file ${file.name} tidak didukung.`);
                updateInputFiles();
                return;
            }

            if (file.size > maxSize) {
                alert(`Ukuran file ${file.name} melebihi 5 MB.`);
                updateInputFiles();
                return;
            }
        }

        selectedFiles.push(...newFiles);
        updateInputFiles();
        renderFileList();
    });

    closeModal.addEventListener('click', () => {
        imageModal.style.display = 'none';
        modalImage.src = '';
    });

    imageModal.addEventListener('click', (event) => {
        if (event.target === imageModal) {
            imageModal.style.display = 'none';
            modalImage.src = '';
        }
    });
</script>