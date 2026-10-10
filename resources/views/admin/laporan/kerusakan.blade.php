<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kerusakan - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Kerusakan - Admin</title>
    
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/laporan/laporan_admin.css') }}">
</head>
</head>
<body>
    <div class="layout">
        <!-- ===== Sidebar ===== -->
        <aside class="sidebar">
            <div class="sidebar-brand"><span>KARSA</span></div>
            <ul class="sidebar-nav">
                <li><a href="{{ route('admin.home') }}">Home</a></li>
                <li><a href="{{ route('users.index') }}">Semua User</a></li>
                <li><a href="{{ route('fasilitas.index') }}">Fasilitas</a></li>
            </ul>
        </aside>

        <!-- ===== Main content ===== -->
        <main class="main">
            <div class="header-actions">
                <div>
                    <h1>Rekap Frekuensi Kerusakan</h1>
                    <p class="subtitle">Jumlah pelaporan kerusakan berdasarkan fasilitas</p>
                </div>
                <div>
    <a href="{{ route('admin.laporan.kerusakan.export', ['type' => 'excel'] + request()->all()) }}" class="btn-report" style="background-color: #059669;">Export Excel</a>
    <a href="{{ route('admin.laporan.kerusakan.export', ['type' => 'pdf'] + request()->all()) }}" class="btn-report" style="background-color: #dc2626;">Export PDF</a>
</div>
            </div>

            <!-- Filter -->
            <div class="filter-container">
                <form action="{{ route('admin.laporan.kerusakan') }}" method="GET" class="filter-form">
                    <div class="form-group">
                        <label>Mulai Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}">
                    </div>
                    <div class="form-group">
                        <label>Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}">
                    </div>
                    <div class="form-group">
                        <label>Lokasi (Opsional)</label>
                        <select name="lokasi">
                            <option value="">Semua Lokasi</option>
                            @foreach($lokasiList as $lokasi)
                                <option value="{{ $lokasi }}" {{ $lokasiFilter == $lokasi ? 'selected' : '' }}>{{ $lokasi }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn">Filter Data</button>
                </form>
            </div>

            <!-- Tabel Data -->
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nama Fasilitas</th>
                            <th>Lokasi</th>
                            <th style="text-align: center;">Total Pelaporan Kerusakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($fasilitas as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td style="text-align: center;"><strong>{{ $item->total_laporan }}</strong> Kali Dilaporkan</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 20px;">Tidak ada data laporan pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>