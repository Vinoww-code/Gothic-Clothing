@extends('admin.layouts.app')

@section('title', 'Manajemen Pesanan Sewa')

@section('content')
<style>
    .filter-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 20px; border-bottom: 1px solid #dee2e6; padding-bottom: 15px; }
    .filter-tab { padding: 7px 14px; border-radius: 4px; font-size: 13px; text-decoration: none; color: #495057; background: #e9ecef; transition: 0.2s; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; }
    .filter-tab:hover { background: #dee2e6; color: #212529; }
    .filter-tab.active { background: #8b0000; color: #fff; }
    .filter-tab .badge-count { background: rgba(0,0,0,0.15); padding: 1px 6px; border-radius: 10px; font-size: 11px; }
    .filter-tab.active .badge-count { background: rgba(255,255,255,0.25); color: #fff; }

    .search-bar-wrapper { margin-bottom: 20px; }
    .search-form { display: flex; gap: 10px; }
    .search-input { flex: 1; padding: 9px 14px; background: #fff; border: 1px solid #ced4da; border-radius: 4px; color: #495057; font-size: 13px; }
    .search-input:focus { outline: none; border-color: #8b0000; box-shadow: 0 0 0 2px rgba(139,0,0,0.15); }
    
    .product-thumb { width: 45px; height: 45px; border-radius: 4px; object-fit: cover; border: 1px solid #ced4da; }
</style>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <a href="{{ route('admin.orders.index') }}" class="filter-tab {{ !request('payment_status') && !request('order_status') ? 'active' : '' }}">
        Semua Pesanan <span class="badge-count">{{ $counts['all'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['payment_status' => 'pending']) }}" class="filter-tab {{ request('payment_status') == 'pending' ? 'active' : '' }}">
        <i class="fa-solid fa-clock"></i> Belum Bayar <span class="badge-count">{{ $counts['pending_payment'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['payment_status' => 'paid']) }}" class="filter-tab {{ request('payment_status') == 'paid' ? 'active' : '' }}">
        <i class="fa-solid fa-circle-check"></i> Sudah Bayar <span class="badge-count">{{ $counts['paid'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['order_status' => 'pending']) }}" class="filter-tab {{ request('order_status') == 'pending' ? 'active' : '' }}">
        <i class="fa-solid fa-hourglass-half"></i> Menunggu Konfirmasi <span class="badge-count">{{ $counts['pending_order'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['order_status' => 'confirmed']) }}" class="filter-tab {{ request('order_status') == 'confirmed' ? 'active' : '' }}">
        <i class="fa-solid fa-thumbs-up"></i> Disetujui (Belum Dikemas) <span class="badge-count">{{ $counts['confirmed'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['order_status' => 'packing']) }}" class="filter-tab {{ request('order_status') == 'packing' ? 'active' : '' }}">
        <i class="fa-solid fa-box"></i> Sedang Dikemas <span class="badge-count">{{ $counts['packing'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['order_status' => 'shipping']) }}" class="filter-tab {{ request('order_status') == 'shipping' ? 'active' : '' }}">
        <i class="fa-solid fa-truck-fast"></i> Sedang Dikirim <span class="badge-count">{{ $counts['shipping'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['order_status' => 'rented']) }}" class="filter-tab {{ request('order_status') == 'rented' ? 'active' : '' }}">
        <i class="fa-solid fa-shirt"></i> Sedang Disewa <span class="badge-count">{{ $counts['rented'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['order_status' => 'completed']) }}" class="filter-tab {{ request('order_status') == 'completed' ? 'active' : '' }}">
        <i class="fa-solid fa-circle-check"></i> Selesai <span class="badge-count">{{ $counts['completed'] }}</span>
    </a>
    <a href="{{ route('admin.orders.index', ['order_status' => 'cancelled']) }}" class="filter-tab {{ request('order_status') == 'cancelled' ? 'active' : '' }}">
        <i class="fa-solid fa-ban"></i> Dibatalkan <span class="badge-count">{{ $counts['cancelled'] }}</span>
    </a>
</div>

<!-- Search Bar -->
<div class="search-bar-wrapper">
    <form action="{{ route('admin.orders.index') }}" method="GET" class="search-form">
        @if(request('payment_status'))
            <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">
        @endif
        @if(request('order_status'))
            <input type="hidden" name="order_status" value="{{ request('order_status') }}">
        @endif
        <input type="text" name="search" class="search-input" placeholder="Cari Kode Order / Nama / NIK / WhatsApp..." value="{{ request('search') }}">
        <button type="submit" class="btn-primary" style="padding: 8px 16px;">
            <i class="fa-solid fa-magnifying-glass"></i> Cari
        </button>
        @if(request('search') || request('payment_status') || request('order_status'))
            <a href="{{ route('admin.orders.index') }}" class="btn-outline">Reset</a>
        @endif
    </form>
</div>

<!-- Orders Table -->
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Kode & Waktu</th>
                <th>Penyewa & Kontak</th>
                <th>Kostum Disewa</th>
                <th>Durasi / Tanggal</th>
                <th>Total Bayar</th>
                <th>Status Bayar</th>
                <th>Status Sewa</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>
                        @if($order->product && $order->product->images->count() > 0)
                            <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" alt="" class="product-thumb">
                        @else
                            <div class="product-thumb" style="background:#e9ecef; display:flex; align-items:center; justify-content:center; color:#6c757d; font-size:10px;">N/A</div>
                        @endif
                    </td>
                    <td>
                        <strong style="color: #212529; font-family: monospace; font-size: 13px;">{{ $order->order_code }}</strong>
                        <div style="font-size: 11px; color: #6c757d;">
                            {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                        </div>
                    </td>
                    <td>
                        <strong style="color: #212529; font-size: 13px;">{{ $order->id_card_name ?? $order->user->name }}</strong>
                        @if($order->whatsapp)
                            <div style="font-size: 12px; color: #6c757d;">
                                <i class="fa-brands fa-whatsapp" style="color:#25d366;"></i> {{ $order->whatsapp }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="color: #212529; font-weight: 500;">{{ $order->product->name ?? 'Produk Dihapus' }}</div>
                        <div style="color: #8b0000; font-size: 11px; text-transform: uppercase;">
                            {{ $order->product->category->name ?? 'Gothic Item' }}
                        </div>
                    </td>
                    <td>
                        <div style="color: #212529; font-weight: 500;">{{ $order->rental_days }} Hari</div>
                        @if($order->start_date && $order->end_date)
                            <div style="font-size: 11px; color: #6c757d;">
                                {{ $order->start_date->format('d/m') }} - {{ $order->end_date->format('d/m/Y') }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <strong style="color: #28a745; font-size: 13px;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                        <div style="font-size: 11px; color: #6c757d; text-transform: uppercase;">{{ $order->payment_method }}</div>
                    </td>
                    <td>
                        <span class="status-badge badge-{{ $order->payment_status }}">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge badge-{{ $order->order_status }}">
                            {{ $order->order_status_label }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-primary" style="padding: 5px 12px; font-size: 12px;">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 30px; color: #6c757d;">
                        Tidak ada data pesanan yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $orders->links() }}
</div>
@endsection
