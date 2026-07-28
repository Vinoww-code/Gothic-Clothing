<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Gothic Clothing</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0a0a0a;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #111;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.8);
        }
        .login-card h2 {
            text-align: center;
            color: #8b0000;
            margin-bottom: 20px;
            font-size: 24px;
            letter-spacing: 2px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #aaa;
            font-size: 13px;
        }
        .form-group input {
            width: 100%;
            padding: 12px;
            background: #1a1a1a;
            border: 1px solid #333;
            color: #fff;
            border-radius: 4px;
            box-sizing: border-box;
            outline: none;
        }
        .form-group input:focus {
            border-color: #8b0000;
        }
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #8b0000;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .btn-login:hover {
            background: #a10000;
        }
        .alert-danger {
            background: rgba(255, 0, 0, 0.1);
            border: 1px solid #ff4444;
            color: #ff4444;
            padding: 10px;
            border-radius: 4px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2><i class="fa-solid fa-user-shield"></i> ADMIN PANEL</h2>

    @if($errors->any())
        <div class="alert-danger">
            {{ $errors->first() }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.login.post') }}" method="POST">
        @csrf
        <div class="form-group">
            <label><i class="fa-solid fa-envelope"></i> Email Admin</label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="admin@example.com" required autofocus>
        </div>

        <div class="form-group">
            <label><i class="fa-solid fa-lock"></i> Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-login">MASUK ADMIN</button>
    </form>
</div>

</body>
</html>