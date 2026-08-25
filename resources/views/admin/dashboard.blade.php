@extends('admin.layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<style>
    .dash-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
    @media (max-width: 1100px) { .dash-stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .dash-stats-grid { grid-template-columns: 1fr; } }
    
    .dash-card { background: #fff; border: 1px solid #e3e6f0; border-radius: 8px; padding: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); }
    .dash-card-info h4 { margin: 0 0 5px; font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 700; }
    .dash-card-info .val { font-size: 20px; font-weight: bold; color: #212529; }
    .dash-card-icon { width: 48px; height: 48px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .icon-revenue { background: #e8f5e9; color: #2e7d32; }
    .icon-pending { background: #fff8e1; color: #f57f17; }
    .icon-products { background: #e3f2fd; color: #1565c0; }
    .icon-orders { background: #fbe9e7; color: #c62828; }

    .quick-actions { display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap; }
</style>

<div class="dash-stats-grid">
    <div class="dash-card">
        <div class="dash-card-info">
            <h4>Pendapatan Bulan Ini</h4>
            <div class="val" style="color: #2e7d32;">Rp {{ number_format($stats['month_revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="dash-card-icon icon-revenue"><i class="fa-solid fa-sack-dollar"></i></div>
    </div>
    <div class="dash-card">
        <div class="dash-card-info">
            <h4>Pesanan Perlu Diproses</h4>
            <div class="val" style="color: #f57f17;">{{ $stats['pending_orders'] }} Pesanan</div>
        </div>
        <div class="dash-card-icon icon-pending"><i class="fa-solid fa-bell"></i></div>
    </div>
    <div class="dash-card">
        <div class="dash-card-info">
            <h4>Total Produk Aktif</h4>
            <div class="val">{{ $stats['available_products'] }} / {{ $stats['total_products'] }}</div>
        </div>
        <div class="dash-card-icon icon-products"><i class="fa-solid fa-shirt"></i></div>
    </div>
    <div class="dash-card">
        <div class="dash-card-info">
            <h4>Total Semua Pesanan</h4>
            <div class="val">{{ $stats['total_orders'] }} Pesanan</div>
        </div>
        <div class="dash-card-icon icon-orders"><i class="fa-solid fa-box-archive"></i></div>
    </div>
</div>

<div class="quick-actions">
    <a href="{{ route('admin.orders.index', ['order_status' => 'pending']) }}" class="btn-primary">
        <i class="fa-solid fa-list-check"></i> Periksa Pesanan Baru
    </a>
    <a href="{{ route('admin.finance.index') }}" class="btn-outline">
        <i class="fa-solid fa-chart-pie"></i> Lihat Laporan Keuangan
    </a>
    <a href="{{ route('admin.products.create') }}" class="btn-outline">
        <i class="fa-solid fa-plus"></i> Tambah Produk Baru
    </a>
</div>

<h3 style="color: #343a40; font-size: 17px; margin: 25px 0 15px; display: flex; align-items: center; justify-content: space-between;">
    <span><i class="fa-solid fa-clock-rotate-left" style="color: #8b0000; margin-right: 6px;"></i> Pesanan Masuk Terkini</span>
    <a href="{{ route('admin.orders.index') }}" style="font-size: 13px; color: #6c757d; text-decoration: none; font-weight: normal;">
        Lihat Semua Pesanan &rarr;
    </a>
</h3>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Kode Pesanan</th>
                <th>Penyewa</th>
                <th>Kostum</th>
                <th>Total Bayar</th>
                <th>Status Bayar</th>
                <th>Status Sewa</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentOrders as $order)
                <tr>
                    <td><strong style="color: #212529; font-family: monospace;">{{ $order->order_code }}</strong></td>
                    <td>{{ $order->id_card_name ?? $order->user->name }}</td>
                    <td>{{ $order->product->name ?? 'Produk Dihapus' }}</td>
                    <td><strong style="color: #28a745;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong></td>
                    <td><span class="status-badge badge-{{ $order->payment_status }}">{{ strtoupper($order->payment_status) }}</span></td>
                    <td><span class="status-badge badge-{{ $order->order_status }}">{{ strtoupper($order->order_status) }}</span></td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-outline" style="padding: 4px 10px; font-size: 12px;">Detail</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #6c757d; padding: 25px;">Belum ada pesanan masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection