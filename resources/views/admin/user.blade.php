<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
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
                <li><a href="{{ route('fasilitas.index') }}">Fasilitas</a></li>
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

                <!-- ===== Search & Filter ===== -->
                <form method="GET" action="{{ route('users.index') }}" class="admin-filter-form">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari berdasarkan nama..."
                        class="filter-input filter-search"
                    >

                    <select name="filter_status" class="filter-input">
                        <option value="">Semua Status</option>
                        <option value="verified" {{ request('filter_status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        <option value="unverified" {{ request('filter_status') == 'unverified' ? 'selected' : '' }}>Belum Diverifikasi</option>
                    </select>

                    <select name="filter_role" class="filter-input">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('filter_role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ request('filter_role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="user" {{ request('filter_role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>

                    <button type="submit" class="btn">Terapkan</button>

                    @if (request('search') || request('filter_status') || request('filter_role'))
                        <a href="{{ route('users.index') }}" class="btn-secondary">Reset</a>
                    @endif
                </form>

                <div class="admin-table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Role</th>
                                <th>Aksi</th>
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
                                    <td>{{ ucfirst($user->role) }}</td>
                                    <td class="admin-actions">
                                        @if ($user->role == 'admin')
                                            <span class="no-action">Tidak ada aksi</span>
                                        @else
                                            @if ($user->role == 'user' && $user->status != 'verified')
                                                <form id="verify-form-{{ $user->id }}" action="{{ route('verifiedUser') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="email" value="{{ $user->email }}">
                                                    <button type="button" class="btn-icon btn-icon-verify" onclick="confirmPopUp('verify-form-{{ $user->id }}', 'Verifikasi User?', 'Status user ini akan diubah permanen!')">Verified</button>
                                                </form>
                                            @endif

                                            <form id="delete-form-{{ $user->id }}" action="{{ route('deleteUser') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="email" value="{{ $user->email }}">
                                                <button type="button" class="btn-icon btn-icon-danger" onclick="confirmPopUp('delete-form-{{ $user->id }}', 'Hapus User?', 'Data user ini akan dihapus permanen!')">Delete</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="empty-note">Tidak ada data user ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="pagination">
                    @if ($users->onFirstPage())
                        <span class="disabled">&laquo; Prev</span>
                    @else
                        <a href="{{ $users->previousPageUrl() }}">&laquo; Prev</a>
                    @endif

                    @for ($page = 1; $page <= $users->lastPage(); $page++)
                        @if ($page == $users->currentPage())
                            <span class="active">{{ $page }}</span>
                        @else
                            <a href="{{ $users->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

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