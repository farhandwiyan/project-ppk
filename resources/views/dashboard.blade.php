<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .dashboard-container {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            max-width: 950px;
            margin: 0 auto;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #eee;
        }

        .dashboard-header h1 {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
        }

        button, .btn {
            padding: 8px 16px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s ease;
        }

        button:hover, .btn:hover {
            background-color: #357abd;
        }

        .btn-logout {
            background-color: #e2e2e2;
            color: #444;
        }

        .btn-logout:hover {
            background-color: #d0d0d0;
        }

        .btn-verify {
            background-color: #2ecc71;
        }

        .btn-verify:hover {
            background-color: #27ae60;
        }

        .btn-danger {
            background-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .alert-success {
            background-color: #eafaf1;
            color: #27ae60;
            border: 1px solid #b7ecd2;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background-color: #fdecea;
            color: #e74c3c;
            border: 1px solid #f5c6cb;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .toolbar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .search-form {
            display: flex;
            gap: 8px;
            flex: 1;
            min-width: 220px;
        }

        .search-form input[type="text"] {
            flex: 1;
            padding: 9px 12px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            font-size: 14px;
        }

        .search-form input[type="text"]:focus {
            outline: none;
            border-color: #4a90e2;
        }

        .filter-form select {
            padding: 9px 12px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            font-size: 14px;
            background-color: white;
            cursor: pointer;
            min-width: 190px;
        }

        .filter-form select:focus {
            outline: none;
            border-color: #4a90e2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        th {
            background-color: #f8f9fb;
            color: #555;
            font-weight: 600;
        }

        tr:hover {
            background-color: #fafbfc;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-verified {
            background-color: #eafaf1;
            color: #27ae60;
        }

        .badge-unverified {
            background-color: #fdf2ea;
            color: #e67e22;
        }

        .badge-role {
            background-color: #eef2ff;
            color: #4a5fc1;
        }

        .action-cell {
            display: flex;
            gap: 6px;
        }

        .no-action {
            color: #bbb;
            font-size: 13px;
            font-style: italic;
        }

        .no-data {
            text-align: center;
            color: #999;
            padding: 30px;
        }

        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination a, .pagination span {
            padding: 6px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-decoration: none;
            color: #333;
            font-size: 13px;
        }

        .pagination a:hover {
            background-color: #f0f0f0;
        }

        .pagination .active {
            background-color: #4a90e2;
            color: white;
            border-color: #4a90e2;
        }

        .pagination .disabled {
            color: #bbb;
        }

        .btn-edit-profile {
            background-color: #f0f3fa;
            color: #4a5fc1;
        }

        .btn-edit-profile:hover {
            background-color: #e2e6f5;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Halo, {{ auth()->user()->nama }}</h1>
            <a href="{{ route('update-user') }}" class="btn btn-edit-profile">Edit Profil</a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        <div class="toolbar">
            <form action="{{ route('dashboard') }}" method="GET" class="search-form">
                <input type="hidden" name="filter" value="{{ request('filter') }}">
                <input type="text" name="search" placeholder="Cari berdasarkan nama..." value="{{ request('search') }}">
                <button type="submit">Cari</button>
            </form>

            <form action="{{ route('dashboard') }}" method="GET" class="filter-form">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <select name="filter" onchange="this.form.submit()">
                    <option value="all" {{ request('filter', 'all') == 'all' ? 'selected' : '' }}>Semua User</option>
                    <option value="unverified" {{ request('filter') == 'unverified' ? 'selected' : '' }}>Belum Diverifikasi</option>
                    <option value="petugas" {{ request('filter') == 'petugas' ? 'selected' : '' }}>Role Petugas</option>
                    <option value="user" {{ request('filter') == 'user' ? 'selected' : '' }}>Role User</option>
                </select>
            </form>

            <a href="{{ route('create-user') }}" class="btn btn-add">+ Tambah User</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration + ($users->currentPage() - 1) * $users->perPage() }}</td>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->status == 'verified')
                                <span class="badge badge-verified">Verified</span>
                            @else
                                <span class="badge badge-unverified">Belum Verified</span>
                            @endif
                        </td>
                        <td><span class="badge badge-role">{{ ucfirst($user->role) }}</span></td>
                        <td>
                            @if ($user->role == 'admin')
                                <span class="no-action">Tidak ada aksi</span>
                            @else
                                <div class="action-cell">
                                    @if ($user->role == 'user' && $user->status != 'verified')
                                        <form action="{{ route('verifiedUser') }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin memverifikasi user {{ $user->nama }}?');">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ $user->email }}">
                                            <button type="submit" class="btn-verify">Verified</button>
                                        </form>
                                    @endif

                                    <form action="{{ route('deleteUser') }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus user {{ $user->nama }}? Tindakan ini tidak bisa dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                        <button type="submit" class="btn-danger">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="no-data">Tidak ada data user ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{-- Tombol Previous --}}
            @if ($users->onFirstPage())
                <span class="disabled">&laquo; Prev</span>
            @else
                <a href="{{ $users->previousPageUrl() }}">&laquo; Prev</a>
            @endif

            {{-- Nomor halaman --}}
            @for ($page = 1; $page <= $users->lastPage(); $page++)
                @if ($page == $users->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $users->url($page) }}">{{ $page }}</a>
                @endif
            @endfor

            {{-- Tombol Next --}}
            @if ($users->hasMorePages())
                <a href="{{ $users->nextPageUrl() }}">Next &raquo;</a>
            @else
                <span class="disabled">Next &raquo;</span>
            @endif
        </div>
    </div>
</body>
</html>