<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
    }

    body {
      background-color: #f2f2f2;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .register-container {
      background: #ffffff;
      padding: 30px 40px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .register-container h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #333;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-size: 14px;
      color: #555;
    }

    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 14px;
    }

    .form-group input:focus {
      outline: none;
      border-color: #4a90e2;
    }

    button {
      width: 100%;
      padding: 10px;
      background-color: #4a90e2;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 15px;
      cursor: pointer;
      margin-top: 10px;
    }

    button:hover {
      background-color: #357abd;
    }

    .login-link {
      text-align: center;
      margin-top: 15px;
      font-size: 13px;
    }

    .login-link a {
      color: #4a90e2;
      text-decoration: none;
    }

    .error {
        color: red;
        text-align: center;
        margin: 1rem;
    }

    .button-home {
        position: fixed;
        top: 20px;
        left: 20px;
    }

    .button-home a {
        display: inline-block;
        padding: 8px 16px;
        background-color: #4a90e2;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
    }

    .button-home a:hover {
        background-color: #357abd;
    }
  </style>
</head>
<body>

    <div class="button-home">
        <a href="{{ route('home') }}">Home</a>
    </div>

  <div class="register-container">
    <h2>Login</h2>
    <form action="{{ route('login') }}" method="POST">
        @csrf

      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit">Login</button>
    </form>

    <p class="login-link">Belum punya akun? <a href="{{ route('register') }}">Register di sini</a></p>
    @error('email')
        <div class="error">{{ $message }}</div>
    @enderror
  </div>

</body>
</html>