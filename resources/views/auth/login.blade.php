<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Gothic Clothing</title>
    <style>
        body { font-family: sans-serif; background: #111; color: #fff; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-box { background: #222; padding: 30px; border-radius: 8px; width: 100%; max-width: 400px; box-shadow: 0 4px 10px rgba(0,0,0,0.5); }
        .login-box h2 { text-align: center; margin-top: 0; color: #fff; margin-bottom: 25px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 14px; color: #ccc; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #444; background: #333; color: #fff; border-radius: 4px; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #8b0000; }
        .btn { width: 100%; padding: 12px; background: #8b0000; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; margin-top: 10px; transition: 0.3s; }
        .btn:hover { background: #660000; }
        .error { color: #ff6b6b; font-size: 14px; margin-bottom: 15px; text-align: center; background: rgba(255, 107, 107, 0.1); padding: 10px; border-radius: 4px;}
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        
        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>