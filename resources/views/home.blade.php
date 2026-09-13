<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .home-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .home-container h1 {
            margin-bottom: 10px;
        }

        .home-container a {
            text-decoration: none;
            color: #4a90e2;
            font-size: 16px;
        }

        .home-container a:hover {
            text-decoration: underline;
        }

        .home-container > .navigasi a {
            padding: 10px 20px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
            text-decoration: none;

            margin: 0rem 0.5rem;
        }

        .home-container > .navigasi a:hover {
            background-color: #357abd;
        }
    </style>
</head>
<body>
    <div class="home-container">
        <h1>Home</h1>
        <div class="navigasi">
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</body>
</html>