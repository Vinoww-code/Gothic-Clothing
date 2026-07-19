<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Gothic Clothing</title>
    <style>
        body { margin: 0; font-family: sans-serif; background: #f4f6f9; display: flex; min-height: 100vh; }
        .sidebar { width: 250px; background: #212529; color: #fff; display: flex; flex-direction: column; }
        .sidebar h2 { text-align: center; padding: 20px 0; margin: 0; background: #1a1e21; font-size: 20px; border-bottom: 1px solid #333; letter-spacing: 1px;}
        .sidebar h2 span { color: #dc3545; }
        .sidebar a { color: #c2c7d0; text-decoration: none; padding: 15px 20px; border-bottom: 1px solid #2c3136; display: block; transition: 0.2s;}
        .sidebar a:hover { background: #343a40; color: #fff; }
        .content { flex: 1; display: flex; flex-direction: column; }
        .header { background: #fff; padding: 15px 25px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #dee2e6; }
        .header .user-info { font-weight: bold; color: #495057; }
        .header .logout-btn { background: transparent; border: none; color: #dc3545; cursor: pointer; font-size: 15px; font-weight: bold; padding: 0; text-decoration: underline; }
        .main { padding: 25px; flex: 1; }
        .card { background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #e3e6f0; }
        h1.page-title { margin-top: 0; color: #343a40; font-size: 24px; margin-bottom: 20px; }
        
        .btn-primary { background: #007bff; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-block;}
        .btn-primary:hover { background: #0069d9; }
        .btn-warning { background: #ffc107; color: #212529; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-block; font-size: 14px;}
        .btn-danger { background: #dc3545; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-block; font-size: 14px;}
        .btn-success { background: #28a745; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-block; font-size: 14px;}
        .btn-secondary { background: #6c757d; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-block; font-size: 14px;}
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { padding: 12px; border: 1px solid #dee2e6; text-align: left; vertical-align: middle; }
        .table th { background: #f8f9fa; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-control { width: 100%; padding: 10px; border: 1px solid #ced4da; border-radius: 4px; box-sizing: border-box; }
        .text-danger { color: #dc3545; font-size: 14px; margin-top: 5px; display: block;}
        .d-flex { display: flex; gap: 10px; }
        .mb-3 { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Gothic <span>Admin</span></h2>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('admin.categories.index') }}">Categories</a>
        <a href="{{ route('admin.products.index') }}">Products</a>
        <a href="{{ route('admin.banners.index') }}">Banners</a>
        <a href="{{ route('admin.bestsellers.index') }}">Best Sellers</a>
        <a href="{{ route('admin.testimonials.index') }}">Testimonials</a>
        <a href="{{ route('admin.faqs.index') }}">FAQ</a>
    </div>
    
    <div class="content">
        <div class="header">
            <div class="user-info">Welcome, {{ Auth::user()->name }}</div>
            <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
        
        <div class="main">
            <h1 class="page-title">@yield('title')</h1>
            <div class="card">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>