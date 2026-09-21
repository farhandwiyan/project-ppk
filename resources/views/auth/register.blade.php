<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
</head>
<body>
  <div class="register-container">
    <div class="register-title">
      <a href="{{ route('home') }}">
        <img src="{{ asset('img/logo-undip.png') }}" alt="Logo Undip" width="63px">
      </a>
      <h2>KARSA</h2>
      <p>Kelola Alat, Ruangan, dan Sarana Akademik.</p>
    </div>

    <form action="{{ route('register') }}" method="POST">
        @csrf

      <div class="form-group">
        <input type="text" id="nama" name="nama" placeholder="Nama" required>
        @error('nama')
            <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Email" required>
        @error('email')
            <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Password" required>
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi Password" required>
      </div>

      <button type="submit">Register</button>
    </form>

    <p class="login-link">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
  </div>

</body>
</html>