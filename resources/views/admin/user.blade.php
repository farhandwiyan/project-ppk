<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/sweetalert-helpers.js') }}"></script>
</head>
<body>
    <div class="layout">

        <!-- ===== Sidebar ===== -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <span>KARSA</span>
            </div>

            <ul class="sidebar-nav">
                <li><a href="{{ route('admin.home') }}">Home</a></li>
                <li><a href="{{ route('users.index') }}" class="active">Semua User</a></li>
            </ul>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </aside>

        <!-- ===== Main content ===== -->
        <main class="main">
            <div class="topbar">
                <div>
                    <h1>Semua User</h1>
                    <p class="subtitle">Kelola seluruh akun user di sistem</p>
                </div>
                <a href="{{ route('create-user') }}" class="btn">+ Tambah User</a>
            </div>

            @if (session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert-error">{{ session('error') }}</div>
            @endif

            <div class="card">
                <div class="toolbar">
                    <form action="{{ route('users.index') }}" method="GET" class="search-form">
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                        <input type="text" name="search" placeholder="Cari berdasarkan nama..." value="{{ request('search') }}">
                        <button type="submit">Cari</button>
                    </form>

                    <form action="{{ route('users.index') }}" method="GET" class="filter-form">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="filter" onchange="this.form.submit()">
                            <option value="all" {{ request('filter', 'all') == 'all' ? 'selected' : '' }}>Semua User</option>
                            <option value="unverified" {{ request('filter') == 'unverified' ? 'selected' : '' }}>Belum Diverifikasi</option>
                            <option value="petugas" {{ request('filter') == 'petugas' ? 'selected' : '' }}>Role Petugas</option>
                            <option value="user" {{ request('filter') == 'user' ? 'selected' : '' }}>Role User</option>
                        </select>
                    </form>
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
                                                <form id='verify-form-{{ $user->id }}' action="{{ route('verifiedUser') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                                    <button type="button" class="btn-verify" onclick="confirmPopUp('verify-form-{{ $user->id }}', 'Verifikasi User?', 'Status user ini akan diubah permanen!')">Verified</button>
                                                </form>
                                            @endif

                                            <form id="delete-form-{{ $user->id }}" action="{{ route('deleteUser') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="email" value="{{ $user->email }}">
                                                <button type="button" class="btn-danger" onclick="confirmPopUp('delete-form-{{ $user->id }}', 'Hapus User?', 'Data user ini akan dihapus permanen!')">Delete</button>
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
        </main>
    </div>
</body>
</html>