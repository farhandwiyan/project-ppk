<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservasi {{ $fasilitas->nama }} - KARSA</title>
    <link rel="stylesheet" href="{{ asset('css/reservations/create.css') }}">
</head>
<body>

    <!-- ===== Navbar  ===== -->
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
            <!-- tambahkan nanti -->
            <a href="">Riwayat</a>
            <a href="" id="profile-icon" aria-label="Profil Saya">
                <i data-feather="user"></i>
            </a>
            <a href="" id="hamburger-menu"><i data-feather="menu"></i></a>
        </div>
    </nav>

    <main class="page-content">
        @php
            $gambarUtama = match (true) {
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'ruangan')  => asset('img/ruang-kelas.png'),
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'aula')     => asset('img/aula.png'),
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'lapangan') => asset('img/lapangan.png'),
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'laboratorium') => asset('img/laboratorium.png'),
                str_contains(strtolower($fasilitas->tipe_fasilitas), 'alat')     => asset('img/alat.png'),
                default => asset('img/ruang-kelas.png'),
            };

            // jika ada gambar tambahan
            $galeri = $fasilitas->galeri ?? [$gambarUtama, $gambarUtama, $gambarUtama];

            $statusLabel = match ($fasilitas->status ?? 'tersedia') {
                'tersedia'        => 'Tersedia',
                'terpakai'        => 'Terpakat',
                'dalam_perbaikan' => 'Dalam Perbaikan',
                default           => 'Tersedia',
            };

            $statusClass = match ($fasilitas->status ?? 'tersedia') {
                'tersedia'        => 'badge-status-tersedia',
                'terpakai'        => 'badge-status-terpakai',
                'dalam_perbaikan' => 'badge-status-perbaikan',
                default           => 'badge-status-tersedia',
            };

            // default tanggal H+2
            $tanggalDefault = ($tanggalTerpilih ?? now()->addDay(2))->format('Y-m-d');
        @endphp

        <!-- ===== Info Fasilitas ===== -->
        <section class="info-card">
            <span class="badge-status {{ $statusClass }}">
                <span class="status-dot"></span> {{ $statusLabel }}
            </span>

            <h1>{{ $fasilitas->nama }}</h1>

            <div class="info-meta">
                <span>
                    Kategori: {{ $fasilitas->tipe_fasilitas }}
                </span>
                <span>
                    Kapasitas Maksimal: {{ $fasilitas->kapasitas }} Orang
                </span>
            </div>
        </section>

        <!-- ===== Galeri Foto ===== -->
        <section class="gallery">
            <div class="gallery-main" style="background-image: url('{{ $gambarUtama }}');"></div>
            <div class="gallery-thumbs">
                @foreach (array_slice($galeri, 0, 3) as $foto)
                    <div class="gallery-thumb" style="background-image: url('{{ $foto }}');"></div>
                @endforeach
            </div>
        </section>

        <!-- ===== Form Reservasi ===== -->
        <section class="reservasi-card">

            <div class="reservasi-header">
                <h2>Form Reservasi</h2>
                <p><span class="required">*</span> Pengajuan reservasi Anda akan ditinjau dan dikonfirmasi terlebih dahulu oleh petugas</p>
            </div>

            @if ($errors->any() && !$errors->has([
                'tanggal', 'start_time', 'end_time', 'nama_pemohon', 'instansi_pemohon',
                'nama_kegiatan', 'deskripsi_kegiatan', 'estimasi_peserta',
                'surat_peminjaman', 'proposal_kegiatan',
            ]))
                {{-- Error umum dari SlotBentrokException (di-attach ke key 'start_time' pada handler) akan
                     otomatis muncul lewat @error di field jam mulai. Blok ini untuk error lain di luar field. --}}
            @endif

            <form action="{{ route('reservations.store', $fasilitas) }}" method="POST" enctype="multipart/form-data" class="reservasi-form">
                @csrf

                {{-- Wajib ada: ReservationService membaca $data['fasilitas_id'] untuk cek bentrok jadwal --}}
                <input type="hidden" name="fasilitas_id" value="{{ $fasilitas->id }}">

                <div class="form-group">
                    <label for="tanggal">Tanggal Kegiatan <span class="required">*</span></label>
                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        value="{{ old('tanggal', $tanggalTerpilih->format('Y-m-d')) }}"
                        required
                    >
                    @error('tanggal')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_time">Mulai (WIB) <span class="required">*</span></label>
                        <input
                            type="time"
                            id="start_time"
                            name="start_time"
                            min="07:00"
                            max="20:00"
                            value="{{ old('start_time', '07:00') }}"
                            required
                        >
                        {{-- SlotBentrokException di-attach ke key 'start_time' — pesan bentrok jadwal muncul di sini --}}
                        @error('start_time')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="end_time">Selesai (WIB) <span class="required">*</span></label>
                        <input
                            type="time"
                            id="end_time"
                            name="end_time"
                            min="07:00"
                            max="20:00"
                            value="{{ old('end_time', '20:00') }}"
                            required
                        >
                        <span class="field-hint">Batas Operasional: 07.00 - 20.00 WIB</span>
                        @error('end_time')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="nama_pemohon">Nama Pemohon <span class="required">*</span></label>
                    <input
                        type="text"
                        id="nama_pemohon"
                        name="nama_pemohon"
                        value="{{ old('nama_pemohon', auth()->user()->nama ?? '') }}"
                        placeholder="Contoh: Max Verstappen"
                        required
                    >
                    @error('nama_pemohon')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="instansi_pemohon">Instansi Pemohon <span class="required">*</span></label>
                    <input
                        type="text"
                        id="instansi_pemohon"
                        name="instansi_pemohon"
                        value="{{ old('instansi_pemohon') }}"
                        placeholder="Organisasi Mahasiswa (BEM/DPM/HMD)"
                        required
                    >
                    @error('instansi_pemohon')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nama_kegiatan">Nama Kegiatan <span class="required">*</span></label>
                    <input
                        type="text"
                        id="nama_kegiatan"
                        name="nama_kegiatan"
                        value="{{ old('nama_kegiatan') }}"
                        placeholder="Contoh: LKMMPD 2026"
                        required
                    >
                    @error('nama_kegiatan')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="deskripsi_kegiatan">Deskripsi Singkat Kegiatan <span class="required">*</span></label>
                    <textarea
                        id="deskripsi_kegiatan"
                        name="deskripsi_kegiatan"
                        rows="2"
                        placeholder="Contoh: Kegiatan ospek jurusan mahasiswa baru tahun 2026"
                        required
                    >{{ old('deskripsi_kegiatan') }}</textarea>
                    @error('deskripsi_kegiatan')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="estimasi_peserta">Estimasi Jumlah Peserta (Maks. {{ $fasilitas->kapasitas }}) <span class="required">*</span></label>
                    <input
                        type="number"
                        id="estimasi_peserta"
                        name="jumlah_peserta"
                        min="1"
                        max="500"
                        value="{{ old('estimasi_peserta') }}"
                        required
                    >
                    @error('jumlah_peserta')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Surat Peminjaman Ruangan <span class="required">*</span></label>
                    <div class="dropzone" data-dropzone-for="surat_peminjaman">
                        <input type="file" id="surat_peminjaman" name="surat_peminjaman" accept="application/pdf" hidden required>
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M8 13L10.5 15.5L16 9.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 4L18 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        <p>Tarik dokumen ke sini atau <span class="dropzone-link">Jelajahi Berkas</span></p>
                        <span class="dropzone-hint">Mendukung format pdf (Maksimal 5 MB)</span>
                        <span class="dropzone-filename"></span>
                    </div>
                    @error('surat_peminjaman')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Proposal Kegiatan</label>
                    <div class="dropzone" data-dropzone-for="proposal_kegiatan">
                        <input type="file" id="proposal_kegiatan" name="proposal_kegiatan" accept="application/pdf" hidden>
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none"><rect x="3" y="3" width="18" height="18" rx="3" stroke="currentColor" stroke-width="1.6"/><path d="M8 13L10.5 15.5L16 9.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 4L18 6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
                        <p>Tarik dokumen ke sini atau <span class="dropzone-link">Jelajahi Berkas</span></p>
                        <span class="dropzone-hint">Mendukung format pdf (Maksimal 5 MB)</span>
                        <span class="dropzone-filename"></span>
                    </div>
                    @error('proposal_kegiatan')
                        <span class="error-text">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">
                    Ajukan Reservasi
                </button>

                <p class="form-footnote">
                    <span class="required">*</span>Catatan: Pembatalan reservasi hanya dapat dilakukan maksimal 3 hari (H-3) sebelum tanggal kegiatan.
                </p>
            </form>
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

    <script>
        // Drag & drop file upload
        document.querySelectorAll('.dropzone').forEach(function (zone) {
            const input = zone.querySelector('input[type="file"]');
            const filenameLabel = zone.querySelector('.dropzone-filename');

            function showFilename() {
                if (input.files.length > 0) {
                    filenameLabel.textContent = input.files[0].name;
                    zone.classList.add('has-file');
                } else {
                    filenameLabel.textContent = '';
                    zone.classList.remove('has-file');
                }
            }

            zone.addEventListener('click', () => input.click());
            input.addEventListener('change', showFilename);

            zone.addEventListener('dragover', function (e) {
                e.preventDefault();
                zone.classList.add('dragover');
            });

            zone.addEventListener('dragleave', function () {
                zone.classList.remove('dragover');
            });

            zone.addEventListener('drop', function (e) {
                e.preventDefault();
                zone.classList.remove('dragover');
                if (e.dataTransfer.files.length > 0) {
                    input.files = e.dataTransfer.files;
                    showFilename();
                }
            });
        });
    </script>
</body>
</html>