@extends('admin.layouts.app')

@section('title', 'Pemantauan Keuangan & Laporan Finansial')

@section('content')
<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 25px; }
    @media (max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 600px) { .stats-grid { grid-template-columns: 1fr; } }
    
    .stat-card { background: #fff; border: 1px solid #e3e6f0; border-radius: 8px; padding: 20px; position: relative; overflow: hidden; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05); }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; }
    .stat-card.paid::before { background: #28a745; }
    .stat-card.pending::before { background: #ffc107; }
    .stat-card.today::before { background: #007bff; }
    .stat-card.month::before { background: #8b0000; }
    
    .stat-card-title { font-size: 12px; color: #6c757d; text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 8px; }
    .stat-card-value { font-size: 22px; font-weight: bold; color: #212529; margin-bottom: 5px; }
    .stat-card-sub { font-size: 12px; color: #6c757d; }

    .analytics-grid { display: grid; grid-template-columns: 1.2fr 1fr; gap: 25px; margin-bottom: 30px; }
    @media (max-width: 900px) { .analytics-grid { grid-template-columns: 1fr; } }
    
    .analytics-card { background: #fff; border: 1px solid #e3e6f0; border-radius: 8px; padding: 20px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05); }
    .analytics-card h3 { margin: 0 0 15px; font-size: 16px; color: #343a40; border-bottom: 1px solid #e3e6f0; padding-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .analytics-card h3 i { color: #8b0000; }

    .progress-item { margin-bottom: 15px; }
    .progress-header { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 6px; color: #495057; }
    .progress-bar-bg { background: #e9ecef; height: 8px; border-radius: 4px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 4px; transition: width 0.4s ease; }

    .filter-box { background: #f8f9fa; border: 1px solid #e3e6f0; border-radius: 8px; padding: 16px; margin-bottom: 20px; }
    .filter-form { display: grid; grid-template-columns: repeat(4, 1fr) auto; gap: 12px; align-items: flex-end; }
    @media (max-width: 900px) { .filter-form { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 600px) { .filter-form { grid-template-columns: 1fr; } }
</style>

<!-- 1. SUMMARY STAT CARDS -->
<div class="stats-grid">
    <div class="stat-card paid">
        <div class="stat-card-title"><i class="fa-solid fa-wallet" style="color: #28a745;"></i> Total Pendapatan Masuk</div>
        <div class="stat-card-value" style="color: #28a745;">Rp {{ number_format($totalPaidRevenue, 0, ',', '.') }}</div>
        <div class="stat-card-sub">Dari {{ $paidTransactionsCount }} transaksi berstatus Lunas</div>
    </div>
    <div class="stat-card pending">
        <div class="stat-card-title"><i class="fa-solid fa-clock-rotate-left" style="color: #d39e00;"></i> Tagihan Menunggu (Pending)</div>
        <div class="stat-card-value" style="color: #856404;">Rp {{ number_format($totalPendingRevenue, 0, ',', '.') }}</div>
        <div class="stat-card-sub">{{ $pendingTransactionsCount }} pesanan menunggu pembayaran</div>
    </div>
    <div class="stat-card month">
        <div class="stat-card-title"><i class="fa-solid fa-calendar-days" style="color: #8b0000;"></i> Pendapatan Bulan Ini</div>
        <div class="stat-card-value">Rp {{ number_format($thisMonthRevenue, 0, ',', '.') }}</div>
        <div class="stat-card-sub">Bulan {{ date('F Y') }}</div>
    </div>
    <div class="stat-card today">
        <div class="stat-card-title"><i class="fa-solid fa-sun" style="color: #007bff;"></i> Pendapatan Hari Ini</div>
        <div class="stat-card-value">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
        <div class="stat-card-sub">{{ date('d M Y') }}</div>
    </div>
</div>

<!-- 2. ANALYTICS & BREAKDOWN -->
<div class="analytics-grid">
    <!-- Breakdown per Metode Pembayaran -->
    <div class="analytics-card">
        <h3><i class="fa-solid fa-credit-card"></i> Distribusi Metode Pembayaran</h3>
        @php
            $colors = ['qris' => '#007bff', 'dana' => '#17a2b8', 'ovo' => '#6f42c1', 'cod' => '#28a745'];
        @endphp
        @foreach($paymentBreakdown as $key => $method)
            <div class="progress-item">
                <div class="progress-header">
                    <span>
                        <i class="fa-solid fa-circle" style="color: {{ $colors[$key] ?? '#6c757d' }}; font-size: 8px;"></i>
                        <strong>{{ $method['label'] }}</strong> ({{ $method['count'] }} Transaksi)
                    </span>
                    <span>
                        <strong>Rp {{ number_format($method['total_amount'], 0, ',', '.') }}</strong>
                        <span style="color: #6c757d; font-size: 12px;">({{ $method['percentage'] }}%)</span>
                    </span>
                </div>
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" style="width: {{ $method['percentage'] }}%; background: {{ $colors[$key] ?? '#8b0000' }};"></div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Top Produk Paling Menghasilkan -->
    <div class="analytics-card">
        <h3><i class="fa-solid fa-crown"></i> Kostum Penghasil Omset Tertinggi</h3>
        @forelse($topProducts as $item)
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0; border-bottom: 1px solid #e9ecef;">
                <div>
                    <strong style="color: #212529; font-size: 14px;">{{ $item->product->name ?? 'Produk Dihapus' }}</strong>
                    <div style="color: #6c757d; font-size: 12px;">{{ $item->total_rentals }} Kali Berhasil Disewa</div>
                </div>
                <div style="text-align: right;">
                    <strong style="color: #28a745; font-size: 14px;">Rp {{ number_format($item->revenue, 0, ',', '.') }}</strong>
                </div>
            </div>
        @empty
            <p style="color: #6c757d; font-size: 13px; text-align: center; padding: 20px 0;">Belum ada data transaksi yang lunas.</p>
        @endforelse
    </div>
</div>

<!-- 3. FILTER MUTASI TRANSAKSI -->
<div class="filter-box">
    <form action="{{ route('admin.finance.index') }}" method="GET" class="filter-form">
        <div>
            <label style="font-size: 12px; color: #495057; font-weight: 600; margin-bottom: 4px; display: block;">Dari Tanggal</label>
            <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-control" style="padding: 8px 10px; font-size: 13px;">
        </div>
        <div>
            <label style="font-size: 12px; color: #495057; font-weight: 600; margin-bottom: 4px; display: block;">Sampai Tanggal</label>
            <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-control" style="padding: 8px 10px; font-size: 13px;">
        </div>
        <div>
            <label style="font-size: 12px; color: #495057; font-weight: 600; margin-bottom: 4px; display: block;">Status Pembayaran</label>
            <select name="payment_status" class="form-control" style="padding: 8px 10px; font-size: 13px;">
                <option value="">Semua Status</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>LUNAS (Paid)</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>PENDING</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>GAGAL</option>
            </select>
        </div>
        <div>
            <label style="font-size: 12px; color: #495057; font-weight: 600; margin-bottom: 4px; display: block;">Metode Pembayaran</label>
            <select name="payment_method" class="form-control" style="padding: 8px 10px; font-size: 13px;">
                <option value="">Semua Metode</option>
                <option value="qris" {{ request('payment_method') == 'qris' ? 'selected' : '' }}>QRIS</option>
                <option value="dana" {{ request('payment_method') == 'dana' ? 'selected' : '' }}>DANA</option>
                <option value="ovo" {{ request('payment_method') == 'ovo' ? 'selected' : '' }}>OVO</option>
                <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD</option>
            </select>
        </div>
        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn-primary" style="padding: 8px 14px; font-size: 13px;">
                <i class="fa-solid fa-filter"></i> Filter
            </button>
            @if(request('from_date') || request('to_date') || request('payment_status') || request('payment_method'))
                <a href="{{ route('admin.finance.index') }}" class="btn-outline" style="padding: 8px 12px; font-size: 13px;">Reset</a>
            @endif
        </div>
    </form>
</div>

<!-- 4. TABEL MUTASI TRANSAKSI KEUANGAN -->
<h3 style="color: #343a40; font-size: 17px; margin: 25px 0 15px; display: flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-table-list" style="color: #8b0000;"></i> Rincian Mutasi Transaksi
</h3>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Waktu Transaksi</th>
                <th>Kode Pesanan</th>
                <th>Penyewa</th>
                <th>Kostum Disewa</th>
                <th>Metode Bayar</th>
                <th>Nominal</th>
                <th>Status</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
                <tr>
                    <td style="color: #6c757d; font-size: 13px;">
                        {{ $trx->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                    </td>
                    <td>
                        <strong style="color: #212529; font-family: monospace;">{{ $trx->order_code }}</strong>
                    </td>
                    <td>
                        <div style="color: #212529; font-weight: 500;">{{ $trx->id_card_name ?? $trx->user->name }}</div>
                    </td>
                    <td>
                        <div style="color: #495057;">{{ $trx->product->name ?? 'Produk Dihapus' }}</div>
                        <div style="color: #6c757d; font-size: 11px;">{{ $trx->rental_days }} Hari</div>
                    </td>
                    <td>
                        <span style="text-transform: uppercase; font-weight: 600; font-size: 12px; color: #495057;">{{ $trx->payment_method }}</span>
                    </td>
                    <td>
                        <strong style="color: {{ $trx->payment_status == 'paid' ? '#28a745' : '#856404' }}; font-size: 14px;">
                            Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                        </strong>
                    </td>
                    <td>
                        <span class="status-badge badge-{{ $trx->payment_status }}">
                            {{ strtoupper($trx->payment_status) }}
                        </span>
                    </td>
                    <td style="text-align: center;">
                        <a href="{{ route('admin.orders.show', $trx->id) }}" class="btn-outline" style="padding: 4px 10px; font-size: 12px;">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 25px; color: #6c757d;">
                        Tidak ada catatan transaksi untuk periode ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $transactions->links() }}
</div>
@endsection
