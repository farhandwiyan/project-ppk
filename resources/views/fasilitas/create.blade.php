<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Fasilitas - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/fasilitas/create.css') }}">
</head>
<body>
    <div class="page-wrapper">
        <div class="form-page">

            <div class="form-page-header">
                <div class="brand">KARSA</div>
                <a href="{{ route('fasilitas.index') }}" class="btn-secondary">&laquo; Kembali</a>
            </div>

            <div class="form-card">
                <h1>Tambah Fasilitas</h1>
                <p class="subtitle">Lengkapi data fasilitas baru di bawah ini</p>

                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif

                <form action="{{ route('fasilitas.store') }}" method="POST" class="admin-form">
                    @csrf

                    <div class="form-group">
                        <label for="nama">Nama Fasilitas</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            maxlength="25"
                            value="{{ old('nama') }}"
                            placeholder="Contoh: Lapangan Basket"
                        >
                        @error('nama')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipe_fasilitas">Tipe Fasilitas</label>
                            <select id="tipe_fasilitas" name="tipe_fasilitas">
                                <option value="" disabled {{ old('tipe_fasilitas') ? '' : 'selected' }}>Pilih Tipe Fasilitas</option>
                                @foreach (['Ruangan', 'Aula', 'Lapangan Olahraga', 'Laboratorium', 'Alat'] as $tipe)
                                    <option value="{{ $tipe }}" {{ old('tipe_fasilitas') == $tipe ? 'selected' : '' }}>
                                        {{ $tipe }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipe_fasilitas')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="lokasi">Lokasi</label>
                            <select id="lokasi" name="lokasi">
                                <option value="" disabled {{ old('lokasi') ? '' : 'selected' }}>Pilih Lokasi</option>
                                @foreach ([
                                    'Fakultas Ekonomika dan Bisnis',
                                    'Fakultas Hukum',
                                    'Fakultas Ilmu Budaya',
                                    'Fakultas Ilmu Sosial dan Ilmu Politik',
                                    'Fakultas Kedokteran',
                                    'Fakultas Kesehatan Masyarakat',
                                    'Fakultas Perikanan dan Ilmu Kelautan',
                                    'Fakultas Peternakan dan Pertanian',
                                    'Fakultas Psikologi',
                                    'Fakultas Sains dan Matematika',
                                    'Fakultas Teknik',
                                    'Muladi Dome',
                                ] as $lokasi)
                                    <option value="{{ $lokasi }}" {{ old('lokasi') == $lokasi ? 'selected' : '' }}>
                                        {{ $lokasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi')
                                <span class="error-text">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="kapasitas">Kapasitas (orang)</label>
                        <input
                            type="number"
                            id="kapasitas"
                            name="kapasitas"
                            min="1"
                            value="{{ old('kapasitas') }}"
                            placeholder="Contoh: 20"
                        >
                        @error('kapasitas')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="4"
                            placeholder="Jelaskan fasilitas ini secara singkat..."
                        >{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Simpan Fasilitas</button>
                        <a href="{{ route('fasilitas.index') }}" class="btn-secondary">Batal</a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</body>
</html>