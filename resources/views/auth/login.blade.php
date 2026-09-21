<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
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

    <form action="{{ route('login') }}" method="POST">
        @csrf

      <div class="form-group">
        <input type="email" id="email" name="email" placeholder="Email" required>
      </div>

      <div class="form-group">
        <input type="password" id="password" name="password" placeholder="Password" required>
      </div>

      @error('email')
        <div class="error">{{ $message }}</div>
      @enderror

      <button type="submit">Login</button>
    </form>

    <p class="login-link">
      Belum punya akun? <a href="{{ route('register') }}">Register disini</a>
    </p>
  </div>

</body>
</html>