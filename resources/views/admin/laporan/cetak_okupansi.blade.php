<!DOCTYPE html>
<html>
<head>
    <title>Cetak Rekap Okupansi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Rekapitulasi Okupansi Fasilitas KARSA</h2>
    <p>Periode: {{ $startDate }} s/d {{ $endDate }}</p>

    <table>
        <thead>
            <tr>
                <th>Nama Fasilitas</th>
                <th>Lokasi</th>
                <th class="text-center">Total Reservasi</th>
                <th class="text-center">Total Jam Pakai</th>
                <th class="text-center">Tingkat Okupansi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fasilitas as $item)
            <tr>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->lokasi }}</td>
                <td class="text-center">{{ $item->total_reservations }}x</td>
                <td class="text-center">{{ $item->total_hours }} Jam</td>
                <td class="text-center">{{ $item->occupancy }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>