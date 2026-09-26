<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            background: #ffffff;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 420px;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #eee;
        }

        .form-header h2 {
            color: #2c3e50;
            font-size: 20px;
        }

        .form-header a {
            font-size: 13px;
            color: #4a90e2;
            text-decoration: none;
        }

        .form-header a:hover {
            text-decoration: underline;
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

        .alert-error ul {
            margin: 0;
            padding-left: 18px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            font-size: 14px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #1a1a4b;
        }

        .form-hint {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }

        .error-text {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 4px;
        }

        button {
            width: 100%;
            padding: 11px;
            background-color: #1a1a4b;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
            margin-top: 8px;
        }

        button:hover {
            background-color: #15153c;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h2>Edit Profil</h2>
            <a href="{{ url()->previous() }}">&larr; Kembali</a>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('updateUser') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" minlength="8" autocomplete="new-password">
                <div class="form-hint">Kosongkan jika tidak ingin mengganti password.</div>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" minlength="8" autocomplete="new-password">
            </div>

            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>