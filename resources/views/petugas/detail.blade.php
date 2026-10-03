<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Reservasi - KARSA</title>
    <!-- Memanggil Tailwind CSS agar desain tetap rapi -->
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/sweetalert-helpers.js') }}"></script>
</head>
<body class="bg-gray-50 p-8">

    <div class="max-w-5xl mx-auto p-6 bg-white rounded-xl shadow-sm border border-gray-100">
        
        <!-- Header -->
        <div class="mb-8 border-b pb-4">
            <p class="text-sm font-semibold text-gray-600 mb-1">Detail Pengajuan Reservasi</p>
            <h1 class="text-3xl font-bold text-[#0F172A] mb-2">{{ $reservasi->fasilitas->nama ?? 'Nama Fasilitas' }}</h1>
            <p class="text-xs text-red-500 font-medium">
                *Pastikan Anda telah memeriksa kelengkapan dokumen dan persyaratan reservasi sebelum memberikan persetujuan
            </p>
        </div>

        <!-- 1. Jadwal Pemakaian Ruangan -->
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 uppercase tracking-wide">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Jadwal Pemakaian Ruangan
                </h2>
                <span class="text-xs text-gray-400 italic">Batas Operasional: 07.00 - 20.00 WIB</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Tanggal Kegiatan</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ \Carbon\Carbon::parse($reservasi->tanggal)->translatedFormat('d F Y') }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Waktu Mulai (WIB)</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2 text-center">
                        {{ \Carbon\Carbon::parse($reservasi->start_time)->format('H:i') }} WIB
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Waktu Selesai (WIB)</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2 text-center">
                        {{ \Carbon\Carbon::parse($reservasi->end_time)->format('H:i') }} WIB
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Profil Pemohon & Instansi -->
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-4 uppercase tracking-wide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                Profil Pemohon & Instansi
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Pemohon</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $reservasi->nama_pemohon }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Instansi</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $reservasi->instansi_pemohon }}
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Agenda Acara -->
        <div class="border border-gray-200 rounded-lg p-6 mb-6">
            <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-4 uppercase tracking-wide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Agenda Acara
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Nama Kegiatan</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $reservasi->nama_kegiatan }}
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-2">Jumlah Kapasitas</label>
                    <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-2">
                        {{ $reservasi->jumlah_peserta }} Orang
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Deskripsi Kegiatan</label>
                <div class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-md px-3 py-3 min-h-[80px]">
                    {{ $reservasi->deskripsi_kegiatan }}
                </div>
            </div>
        </div>

        <!-- 4. Dokumen & Berkas -->
        <div class="border border-gray-200 rounded-lg p-6 mb-8">
            <h2 class="text-sm font-bold text-gray-800 flex items-center gap-2 mb-4 uppercase tracking-wide">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Dokumen & Berkas
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Surat Peminjaman -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-800 mb-1">Surat Peminjaman Ruangan</p>
                        @if($reservasi->surat_peminjaman_path)
                            <a href="{{ asset('storage/' . $reservasi->surat_peminjaman_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat Dokumen &rarr;</a>
                        @else
                            <span class="text-xs text-red-500">Berkas tidak diunggah</span>
                        @endif
                    </div>
                    <span class="bg-green-100 text-green-700 text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider">Tervalidasi Format</span>
                </div>

                <!-- Proposal Kegiatan -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex justify-between items-start">
                    <div>
                        <p class="text-sm font-semibold text-gray-800 mb-1">Proposal Kegiatan</p>
                        @if($reservasi->proposal_kegiatan_path)
                            <a href="{{ asset('storage/' . $reservasi->proposal_kegiatan_path) }}" target="_blank" class="text-xs text-blue-600 hover:underline">Lihat Dokumen &rarr;</a>
                        @else
                            <span class="text-xs text-gray-500">Berkas tidak diunggah</span>
                        @endif
                    </div>
                    <span class="bg-gray-200 text-gray-600 text-[10px] px-2 py-1 rounded font-bold uppercase tracking-wider">Opsional</span>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi Bawah -->
        <div class="flex justify-between items-center pt-4 border-t">
            <a href="{{ route('petugas.home') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 font-semibold hover:bg-gray-50">
                Kembali
            </a>
            
            @if(strtolower($reservasi->status) == 'menunggu')
            <div class="flex gap-3">
                <form id="form-setuju" action="{{ route('petugas.reservasi.setuju', $reservasi->id) }}" method="POST">
                    @csrf @method('PATCH')
                </form>

                <button
                    type="button"
                    onclick="confirmPopUp('form-setuju', 'Setujui Reservasi?', 'Reservasi ini akan disetujui dan slot jadwalnya terkunci untuk fasilitas ini.')"
                    class="px-8 py-2 bg-[#0F766E] text-white rounded-lg font-semibold hover:bg-teal-800 cursor-pointer"
                >
                    Setujui
                </button>

                <form id="form-tolak" action="{{ route('petugas.reservasi.tolak', $reservasi->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <input type="hidden" name="alasan" id="alasan-cancel">
                    </form>
                    <button
                        type="button"
                        onclick="confirmWithInput('form-tolak', 'Tolak Reservasi?', 'Masukkan alasan penolakan reservasi ini.', 'Alasan Penolakan', 'Contoh: Fasilitas sedang dipakai acara lain')"
                        class="px-8 py-2 bg-[#B91C1C] text-white rounded-lg font-semibold hover:bg-red-800 cursor-pointer"
                    >
                        Tolak
                    </button>
                </div>
            @else
                <div class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg font-semibold uppercase text-sm">
                    Status: {{ $reservasi->status }}
                </div>
            @endif

        </div>

    </div>

</body>
</html>