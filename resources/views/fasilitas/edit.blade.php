<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fasilitas - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/fasilitas/edit.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/sweetalert-helpers.js') }}"></script>
</head>
<body>
    <div class="page-wrapper">
        <div class="form-page">

            <div class="form-page-header">
                <div class="brand">KARSA</div>
                <a href="{{ route('fasilitas.index') }}" class="btn-secondary">&laquo; Kembali</a>
            </div>

            <div class="form-card">
                <h1>Edit Fasilitas</h1>
                <p class="subtitle">Perbarui data fasilitas "{{ $fasilitas->nama }}"</p>

                @if (session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif

                <form action="{{ route('fasilitas.update', $fasilitas->id) }}" method="POST" class="admin-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="nama">Nama Fasilitas</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            maxlength="25"
                            value="{{ old('nama', $fasilitas->nama) }}"
                        >
                        @error('nama')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="tipe_fasilitas">Tipe Fasilitas</label>
                            <select id="tipe_fasilitas" name="tipe_fasilitas">
                                @foreach (['Ruangan', 'Aula', 'Lapangan Olahraga', 'Laboratorium', 'Alat'] as $tipe)
                                    <option value="{{ $tipe }}" {{ old('tipe_fasilitas', $fasilitas->tipe_fasilitas) == $tipe ? 'selected' : '' }}>
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
                                    <option value="{{ $lokasi }}" {{ old('lokasi', $fasilitas->lokasi) == $lokasi ? 'selected' : '' }}>
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
                            value="{{ old('kapasitas', $fasilitas->kapasitas) }}"
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
                        >{{ old('deskripsi', $fasilitas->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <span class="error-text">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('fasilitas.index') }}" class="btn-secondary">Batal</a>
                    </div>
                </form>

                <hr class="danger-divider">

                <div class="danger-zone">
                    <div>
                        <h3>Hapus Fasilitas</h3>
                        <p>Tindakan ini tidak dapat dibatalkan. Fasilitas akan dihapus permanen dari sistem.</p>
                    </div>

                    <form id="delete-fasilitas-form" action="{{ route('fasilitas.delete', $fasilitas->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            type="button"
                            class="btn-danger-outline"
                            onclick="confirmPopUp('delete-fasilitas-form', 'Hapus Fasilitas?', 'Data fasilitas \'{{ $fasilitas->nama }}\' akan dihapus permanen dan tidak dapat dikembalikan!')"
                        >
                            Hapus Fasilitas
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</body>
</html>