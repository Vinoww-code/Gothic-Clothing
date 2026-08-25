<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - Gothic Clothing Admin</title>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background: #f4f6f9; color: #333; display: flex; min-height: 100vh; }
        .sidebar { width: 260px; background: #212529; color: #fff; display: flex; flex-direction: column; flex-shrink: 0; }
        .sidebar h2 { text-align: center; padding: 20px 15px; margin: 0; background: #1a1e21; font-size: 19px; border-bottom: 1px solid #333; letter-spacing: 1px; color: #fff; }
        .sidebar h2 span { color: #dc3545; }
        .sidebar-menu { padding: 10px 0; flex: 1; overflow-y: auto; }
        .sidebar-heading { font-size: 11px; text-transform: uppercase; color: #868e96; padding: 12px 20px 6px; font-weight: bold; letter-spacing: 0.8px; }
        .sidebar a { color: #c2c7d0; text-decoration: none; padding: 12px 20px; display: flex; align-items: center; justify-content: space-between; transition: 0.2s; font-size: 14px; border-bottom: 1px solid #2c3136; }
        .sidebar a .menu-left { display: flex; align-items: center; gap: 10px; }
        .sidebar a:hover, .sidebar a.active { background: #343a40; color: #fff; }
        .sidebar .badge { background: #dc3545; color: white; padding: 2px 7px; border-radius: 10px; font-size: 11px; font-weight: bold; }
        
        .content { flex: 1; display: flex; flex-direction: column; min-width: 0; }
        .header { background: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #dee2e6; }
        .header .user-info { font-weight: 600; color: #495057; display: flex; align-items: center; gap: 8px; font-size: 14px; }
        .header .user-info i { color: #8b0000; }
        .header .header-actions { display: flex; align-items: center; gap: 15px; }
        .header .logout-btn { background: transparent; border: none; color: #dc3545; cursor: pointer; font-size: 14px; font-weight: 600; padding: 0; text-decoration: underline; display: inline-flex; align-items: center; gap: 5px; }
        .header .view-site-link { color: #6c757d; text-decoration: none; font-size: 13px; display: inline-flex; align-items: center; gap: 5px; }
        .header .view-site-link:hover { color: #212529; }
        
        .main { padding: 25px 30px; flex: 1; overflow-y: auto; }
        .card { background: #fff; padding: 25px; border-radius: 8px; border: 1px solid #e3e6f0; box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 120, 0.05); }
        h1.page-title { margin-top: 0; color: #343a40; font-size: 22px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; font-weight: 600; }
        
        .btn-primary { background: #8b0000; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; border: 1px solid #8b0000; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; transition: 0.2s; }
        .btn-primary:hover { background: #660000; border-color: #660000; color: #fff; }
        .btn-outline { background: #fff; color: #495057; padding: 7px 14px; text-decoration: none; border-radius: 4px; border: 1px solid #ced4da; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 500; transition: 0.2s; }
        .btn-outline:hover { background: #e9ecef; color: #212529; }
        .btn-warning { background: #ffc107; color: #212529; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 600; }
        .btn-danger { background: #dc3545; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 600; }
        .btn-success { background: #28a745; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 600; }
        .btn-secondary { background: #6c757d; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 600; }
        
        .table-responsive { overflow-x: auto; width: 100%; }
        .table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .table th, .table td { padding: 12px 15px; border: 1px solid #dee2e6; text-align: left; vertical-align: middle; font-size: 13px; }
        .table th { background: #f8f9fa; color: #495057; font-weight: 600; }
        .table tr:hover td { background: #f8f9fa; }
        
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; margin-bottom: 6px; font-weight: 600; font-size: 13px; color: #495057; }
        .form-control { width: 100%; padding: 9px 12px; background: #fff; border: 1px solid #ced4da; border-radius: 4px; color: #495057; font-size: 13px; box-sizing: border-box; }
        .form-control:focus { outline: none; border-color: #8b0000; box-shadow: 0 0 0 2px rgba(139,0,0,0.15); }
        .text-danger { color: #dc3545; font-size: 13px; margin-top: 4px; display: block;}
        .d-flex { display: flex; gap: 10px; }
        .mb-3 { margin-bottom: 16px; }
        
        .alert { padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 8px; }
        .alert-success { background: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
        .alert-danger { background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
        
        /* Status Badges - Clean Solid */
        .status-badge { display: inline-block; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-pending { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
        .badge-paid { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-failed { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .badge-confirmed { background: #cce5ff; color: #004085; border: 1px solid #b8daff; }
        .badge-packing { background: #e2d9f3; color: #563d7c; border: 1px solid #d1c4e9; }
        .badge-shipping { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .badge-rented { background: #ffe8d6; color: #d35400; border: 1px solid #fcd3b6; }
        .badge-completed { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .badge-cancelled { background: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; }
    </style>
</head>
<body>
    @php
        $pendingOrdersCount = \App\Models\Order::where('order_status', 'pending')->orWhere('payment_status', 'pending')->count();
    @endphp
    <div class="sidebar">
        <h2>Gothic <span>Admin</span></h2>
        <div class="sidebar-menu">
            <div class="sidebar-heading">Ringkasan & Finansial</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-gauge-high"></i> Dashboard</div>
            </a>
            <a href="{{ route('admin.finance.index') }}" class="{{ request()->routeIs('admin.finance.*') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-chart-line"></i> Laporan Keuangan</div>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="{{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-box-archive"></i> Pesanan / Orders</div>
                @if($pendingOrdersCount > 0)
                    <span class="badge">{{ $pendingOrdersCount }}</span>
                @endif
            </a>

            <div class="sidebar-heading">Katalog Produk</div>
            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-shirt"></i> Products</div>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-tags"></i> Categories</div>
            </a>
            <a href="{{ route('admin.bestsellers.index') }}" class="{{ request()->routeIs('admin.bestsellers.*') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-star"></i> Best Sellers</div>
            </a>

            <div class="sidebar-heading">Konten & Media</div>
            <a href="{{ route('admin.banners.index') }}" class="{{ request()->routeIs('admin.banners.*') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-image"></i> Banners</div>
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-comment-dots"></i> Testimonials</div>
            </a>
            <a href="{{ route('admin.faqs.index') }}" class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                <div class="menu-left"><i class="fa-solid fa-circle-question"></i> FAQ</div>
            </a>
        </div>
    </div>
    
    <div class="content">
        <div class="header">
            <div class="user-info">
                <i class="fa-solid fa-user-shield"></i>
                Selamat Datang, <strong>{{ Auth::user()->name }}</strong>
            </div>
            <div class="header-actions">
                <a href="{{ route('home') }}" target="_blank" class="view-site-link">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website
                </a>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        
        <div class="main">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}
                </div>
            @endif

            <h1 class="page-title">@yield('title')</h1>
            <div class="card">
                @yield('content')
            </div>
        </div>
    </div>
</body>
</html>