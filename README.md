# KARSA

**KARSA** adalah platform web untuk mengelola reservasi penggunaan fasilitas kampus (ruang kelas, aula, laboratorium, alat, dan lapangan) sekaligus menangani pelaporan kerusakan fasilitas secara terpusat. Dibangun sebagai bagian dari Project PPK 2026 – Web Platform.

Sistem melayani empat aktor dengan hak akses berbeda: **Pengunjung** (tanpa login), **User** (mahasiswa/dosen/staf), **Petugas**, dan **Admin**, mulai dari pencarian ketersediaan fasilitas, pengajuan & persetujuan reservasi, hingga pelaporan dan penanganan kerusakan fasilitas.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Aktor & Role](#aktor--role)
- [Tech Stack](#tech-stack)
- [Struktur Folder](#struktur-folder)
- [Instalasi & Menjalankan Aplikasi](#instalasi--menjalankan-aplikasi)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Akun Default](#akun-default)
- [Aturan Bisnis Utama](#aturan-bisnis-utama)
- [Anggota Tim & Pembagian Tugas](#anggota-tim--pembagian-tugas)
- [Lisensi](#lisensi)

---

## Fitur Utama

| ID | Modul | Fitur |
|---|---|---|
| FR-001 | Authentication & User Role Management | Register (role user), login, logout, dengan role `user` / `petugas` / `admin` |
| FR-002 | User Management | Admin menambahkan akun user/petugas dan memverifikasi akun hasil registrasi mandiri |
| FR-003 | Facility Management | Admin mengelola fasilitas: tambah, ubah, nonaktifkan |
| FR-004 | Facility Search and Catalog | User/pengunjung mencari fasilitas berdasarkan tipe, lokasi, dan kapasitas |
| FR-005 | Facility Search and Catalog | Menampilkan ketersediaan & status fasilitas berdasarkan waktu tertentu tanpa login |
| FR-006 | Facility Reservation Request | User mengajukan reservasi (tanggal, waktu penggunaan, tujuan penggunaan) |
| FR-007 | Facility Reservation Request | Validasi waktu reservasi pada jam operasional (07.00–20.00) |
| FR-008 | History and Status Tracking | User melihat riwayat & status reservasi (menunggu, diterima, ditolak, dibatalkan) |
| FR-009 | Reservation Cancellation | User membatalkan reservasi sebelum batas waktu tertentu |
| FR-010 | Reservation Approval Management | Petugas menyetujui/menolak reservasi serta memproses laporan kerusakan |
| FR-011 | Reservation Approval Management | Petugas melihat dashboard daftar pengajuan reservasi |
| FR-012 | Reservation Approval Management | Sistem mencegah persetujuan reservasi yang bentrok jadwal pada fasilitas yang sama |
| FR-013 | Submitting a Damage Report | User membuat laporan kerusakan (kategori, deskripsi, bukti foto) |
| FR-014 | Report Status Tracking | Sistem menampilkan perkembangan status laporan kerusakan |
| FR-015 | Report Processing and Validation | Petugas memproses/memvalidasi laporan, mengubah status (baru, diproses, selesai, ditolak), memberi catatan penyelesaian |
| FR-016 | Report Processing and Validation | Petugas mengubah status fasilitas: aktif, dalam perbaikan, atau tidak tersedia |
| FR-017 | Report Processing and Validation | Petugas menolak reservasi terdekat pada fasilitas yang sedang dalam perbaikan |
| FR-018 | Reporting & Export | Admin melihat rekap penggunaan fasilitas dan jumlah kerusakan |
| FR-019 | Reporting & Export | Admin mengekspor laporan sistem dalam format CSV, Excel, atau PDF |

## Aktor & Role

| Role | Akses |
|---|---|
| **Pengunjung** | Melihat daftar fasilitas & ketersediaan (tanpa detail pemohon/tujuan), tanpa login |
| **User** | Registrasi/login, ajukan & batalkan reservasi, lihat riwayat, buat & pantau laporan kerusakan |
| **Petugas** | Kelola antrian reservasi & laporan, ubah status fasilitas, cegah bentrok jadwal |
| **Admin** | Kelola akun (user & petugas), verifikasi registrasi, kelola data fasilitas, rekap & ekspor laporan |

## Tech Stack

- **Backend:** Laravel (PHP)
- **Frontend:** Blade template, HTML, CSS, JavaScript 
- **Database:** MySQL (via Eloquent ORM)
- **Version Control:** GitHub 

## Struktur Folder

Laravel sudah secara natural memisahkan koneksi database, tampilan, dan logika proses sesuai ketentuan proyek (`/public`, `/app`, `/views`, `/config`):

```
project_ppk/
├── app/
│   ├── Http/
│   │   └── Controllers/   # logika proses (controller)
│   └── Models/            # model / representasi tabel database
├── database/
│   ├── migrations/        # skema tabel (users, facilities, reservations, reports)
│   └── seeders/           # data awal (akun admin/petugas/user default)
├── public/                # entry point (index.php) & asset publik
├── resources/
│   └── views/             # tampilan (Blade template)
├── routes/
│   └── web.php            # daftar route aplikasi
├── config/
│   └── database.php       # konfigurasi koneksi database
├── .env.example
└── README.md
```

## Instalasi & Menjalankan Aplikasi

Prasyarat: PHP >= 8.1, Composer, Node.js & npm, MySQL/MariaDB.

1. **Clone repository**
   ```bash
   git clone https://github.com/farhandwiyan/project-ppk
   cd project-ppk
   ```
2. **Install dependency PHP (Laravel)**
   ```bash
   composer install
   ```
3. **Salin file environment**
   ```bash
   cp .env.example .env
   ```
4. **Generate application key**
   ```bash
   php artisan key:generate
   ```
5. **Buat database** (mis. `project_ppk`) di MySQL/MariaDB, lalu sesuaikan kredensial pada `.env` (lihat [Konfigurasi Environment](#konfigurasi-environment)).
7. **Jalankan migration** (dan seeder untuk data/akun awal)
   ```bash
   php artisan migrate --seed
   ```
8. **Jalankan aplikasi**
   ```bash
   composer run dev
   ```
9. Akses aplikasi melalui `http://localhost:8000`.

## Konfigurasi Environment

Variabel utama pada `.env`:

| Variabel | Keterangan |
|---|---|
| `APP_NAME` | Nama aplikasi (KARSA) |
| `APP_URL` | URL aplikasi, mis. `http://localhost:8000` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | Host database, mis. `127.0.0.1` |
| `DB_PORT` | Port database, mis. `3306` |
| `DB_DATABASE` | Nama database, mis. `project_ppk` |
| `DB_USERNAME` | Username database, mis. `root` |
| `DB_PASSWORD` | Password database, `kosongkan jika tidak ada password` |

## Akun Default

| Role | Email | Password |
|---|---|---|
| Admin | `admin@example.ocom` | sangat rahasia |

## Aturan Bisnis Utama

- Reservasi hanya berlaku pada jam operasional **07.00–20.00**, tervalidasi di sisi server.
- Sistem mencegah **bentrok jadwal** (double-booking) pada fasilitas yang sama.
- Registrasi mandiri hanya untuk role **user**; akun **petugas** hanya dibuat oleh admin.
- Akun hasil registrasi mandiri harus **diverifikasi admin** sebelum bisa login.
- Fasilitas yang sedang **dalam perbaikan** tidak dapat menerima reservasi baru pada rentang waktu terkait.

## Anggota Tim & Pembagian Tugas

| Nama | NIM | Tugas |
|---|---|---|
| Farhan Muhtaram | 24060124140185 | _(diisi)_ |
| Farhan Dwiyan Akbar | 24060124140137 | _(diisi)_ |
| Claudia Meitania Putri | 24060124140188 | _(diisi)_ |
| Adelia Clearesta | 24060124140204 | _(diisi)_ |

## Lisensi

Project ini dibuat untuk keperluan akademik — Project PPK 2026 – Web Platform.