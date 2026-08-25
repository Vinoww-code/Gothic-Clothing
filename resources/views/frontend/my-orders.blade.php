@extends('layouts.frontend')

@section('content')
<style>
    .orders-wrapper {
        max-width: 960px;
        margin: 0 auto;
    }

    .order-history-card {
        background: var(--bg-light);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 24px;
        margin-bottom: 24px;
        transition: border-color 0.3s ease;
    }

    .order-history-card:hover {
        border-color: var(--primary-color);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 14px;
        margin-bottom: 18px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .order-body {
        display: flex;
        gap: 22px;
        align-items: center;
        flex-wrap: wrap;
    }

    .order-product-img {
        width: 85px;
        height: 85px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #333;
    }

    .order-info {
        flex: 1;
        min-width: 220px;
    }

    .order-status-badges {
        display: flex;
        gap: 8px;
        align-items: center;
        flex-wrap: wrap;
    }

    /* Clean Solid Badges (Harmonious with Home Style) */
    .status-badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-pending { background: #d97706; color: #fff; }
    .status-paid { background: #15803d; color: #fff; }
    .status-failed { background: #b91c1c; color: #fff; }
    
    .stage-pending { background: #b45309; color: #fff; }
    .stage-confirmed { background: #1d4ed8; color: #fff; }
    .stage-packing { background: #7e22ce; color: #fff; }
    .stage-shipping { background: #0369a1; color: #fff; }
    .stage-rented { background: #c2410c; color: #fff; }
    .stage-completed { background: #15803d; color: #fff; }
    .stage-cancelled { background: #475569; color: #fff; }

    /* Solid Buttons Without Glow */
    .btn-solid-primary {
        background-color: var(--primary-color);
        color: #fff;
        padding: 8px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid var(--primary-color);
        transition: 0.2s ease;
    }
    .btn-solid-primary:hover {
        background-color: var(--primary-hover);
        border-color: var(--primary-hover);
        color: #fff;
    }

    .btn-solid-cancel {
        background: transparent;
        color: #f87171;
        border: 1px solid #7f1d1d;
        padding: 7px 14px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: 0.2s ease;
    }
    .btn-solid-cancel:hover {
        background: #991b1b;
        border-color: #991b1b;
        color: #fff;
    }

    .cancellation-box {
        background: #181111;
        border: 1px solid #451a1a;
        border-radius: 6px;
        padding: 12px 16px;
        margin-top: 16px;
        font-size: 13px;
        color: #fca5a5;
    }

    /* Modal */
    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; justify-content: center; align-items: center; padding: 20px; }
    .modal-overlay.active { display: flex; }
    .modal-box { background: #1a1a1a; border: 1px solid #333; border-radius: 8px; width: 100%; max-width: 480px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.8); }
    .modal-box h3 { margin: 0 0 12px; color: #fff; font-size: 18px; display: flex; align-items: center; gap: 8px; font-family: 'Playfair Display', serif; }
    .modal-box p { color: var(--text-light); font-size: 14px; line-height: 1.6; margin-bottom: 15px; }
</style>

<div class="container section-padding" style="padding-top: 120px; padding-bottom: 80px;">
    <div class="orders-wrapper">
        <div style="text-align: center; margin-bottom: 40px;">
            <h1 class="section-title" style="margin-bottom: 10px;">Riwayat Pesanan Saya</h1>
            <p style="color: var(--text-muted); font-size: 14px; max-width: 600px; margin: 0 auto;">
                Pantau status pembayaran, tahapan pengemasan, dan masa sewa kostum gothic Anda.
            </p>
        </div>

        @forelse($orders as $order)
            <div class="order-history-card">
                <div class="order-header">
                    <div>
                        <strong style="color: #fff; font-family: monospace; font-size: 14px; letter-spacing: 0.5px;">{{ $order->order_code }}</strong>
                        <span style="color: var(--text-muted); font-size: 12px; margin-left: 12px;">
                            Dipesan: {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                        </span>
                    </div>
                    <div class="order-status-badges">
                        <!-- Badge Status Bayar -->
                        <span class="status-badge status-{{ $order->payment_status }}">
                            <i class="fa-solid fa-wallet"></i> Bayar: {{ strtoupper($order->payment_status) }}
                        </span>

                        <!-- Badge Status Tahapan Sewa -->
                        @php
                            $stageLabels = [
                                'pending' => 'Menunggu Pembayaran',
                                'confirmed' => 'Disetujui (Belum Dikemas)',
                                'packing' => 'Sedang Dikemas',
                                'shipping' => 'Sedang Dikirim',
                                'rented' => 'Sedang Disewa',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                            ];
                            $stageIcons = [
                                'pending' => 'fa-hourglass-half',
                                'confirmed' => 'fa-circle-check',
                                'packing' => 'fa-box',
                                'shipping' => 'fa-truck-fast',
                                'rented' => 'fa-shirt',
                                'completed' => 'fa-flag-checkered',
                                'cancelled' => 'fa-ban',
                            ];
                        @endphp
                        <span class="status-badge stage-{{ $order->order_status }}">
                            <i class="fa-solid {{ $stageIcons[$order->order_status] ?? 'fa-info' }}"></i> 
                            {{ $stageLabels[$order->order_status] ?? strtoupper($order->order_status) }}
                        </span>
                    </div>
                </div>

                <div class="order-body">
                    @if($order->product && $order->product->images->count() > 0)
                        <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" alt="{{ $order->product->name }}" class="order-product-img">
                    @else
                        <div class="order-product-img" style="background:#222; display:flex; align-items:center; justify-content:center; color:#555; font-size:11px;">No Image</div>
                    @endif

                    <div class="order-info">
                        <h3 style="margin: 0 0 6px; font-size: 18px; color: #fff; font-family: 'Playfair Display', serif;">
                            {{ $order->product->name ?? 'Produk Dihapus' }}
                        </h3>
                        <div style="color: var(--primary-color); font-size: 12px; text-transform: uppercase; font-weight: 600; margin-bottom: 6px;">
                            {{ $order->product->category->name ?? 'Gothic Item' }}
                        </div>
                        <div style="color: var(--text-muted); font-size: 13px;">
                            Periode Sewa: 
                            <strong style="color: #fff;">
                                @if($order->start_date && $order->end_date)
                                    {{ $order->start_date->format('d/m/Y') }} - {{ $order->end_date->format('d/m/Y') }} ({{ $order->rental_days }} Hari)
                                @else
                                    {{ $order->rental_days }} Hari
                                @endif
                            </strong>
                        </div>
                        <div style="color: var(--text-muted); font-size: 13px; margin-top: 2px;">
                            Metode Pengambilan: <strong style="color: #fff; text-transform: uppercase;">{{ $order->delivery_method }}</strong>
                        </div>
                    </div>

                    <div style="text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                        <div>
                            <div style="font-size: 12px; color: var(--text-muted);">Total Tagihan:</div>
                            <div style="font-size: 18px; font-weight: bold; color: #4ade80;">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </div>
                        </div>

                        <div style="display: flex; gap: 8px; flex-wrap: wrap; justify-content: flex-end;">
                            <a href="{{ route('checkout.success', $order->order_code) }}" class="btn-solid-primary">
                                <i class="fa-solid fa-receipt"></i> Detail & Invoice
                            </a>

                            <!-- Tombol Batalkan Pesanan jika memenuhi syarat -->
                            @if($order->isCancellable())
                                <button type="button" class="btn-solid-cancel" onclick="openCancelModal('{{ $order->id }}', '{{ $order->order_code }}', '{{ $order->payment_status }}')">
                                    <i class="fa-solid fa-ban"></i> Batalkan
                                </button>
                            @elseif(in_array($order->order_status, ['packing', 'shipping', 'rented']))
                                <span style="font-size: 11px; color: #94a3b8; display: inline-flex; align-items: center; gap: 4px; padding: 6px 10px; background: #1a1a1a; border-radius: 4px; border: 1px solid #333;">
                                    <i class="fa-solid fa-lock" style="color: #fbbf24;"></i> Sedang diproses (Terkunci)
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Box Keterangan Jika Dibatalkan -->
                @if($order->order_status === 'cancelled')
                    <div class="cancellation-box">
                        <strong><i class="fa-solid fa-circle-info"></i> Pesanan Ini Telah Dibatalkan:</strong>
                        <div style="margin-top: 4px;">
                            Alasan: <em>"{{ $order->cancellation_reason ?? 'Dibatalkan oleh pelanggan' }}"</em>
                            @if($order->cancelled_at)
                                <span style="color: #94a3b8; font-size: 11px; margin-left: 8px;">
                                    (Waktu: {{ $order->cancelled_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB)
                                </span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align: center; padding: 60px 20px; background: var(--bg-light); border: 1px solid var(--border-color); border-radius: 8px;">
                <i class="fa-solid fa-box-open" style="font-size: 48px; color: #444; margin-bottom: 15px;"></i>
                <h3 style="color: #fff; margin-bottom: 10px; font-family: 'Playfair Display', serif;">Belum Ada Riwayat Pesanan</h3>
                <p style="color: var(--text-muted); margin-bottom: 20px;">
                    Anda belum pernah menyewa kostum atau aksesoris gothic kami.
                </p>
                <a href="{{ route('collection') }}" class="btn btn-primary">
                    <i class="fa-solid fa-shirt"></i> Jelajahi Koleksi Sekarang
                </a>
            </div>
        @endforelse

        <div style="margin-top: 25px;">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<!-- Modal Dialog Pembatalan Pesanan -->
<div id="cancelModal" class="modal-overlay" onclick="closeCancelModal()">
    <div class="modal-box" onclick="event.stopPropagation()">
        <h3><i class="fa-solid fa-triangle-exclamation" style="color: #f87171;"></i> Batalkan Pesanan</h3>
        
        <p id="cancelModalDesc">
            Apakah Anda yakin ingin membatalkan pesanan ini? Kostum akan dilepaskan dan kembali tersedia di katalog.
        </p>

        <form id="cancelOrderForm" method="POST" action="">
            @csrf
            <div id="reasonInputGroup" style="display: none; margin-bottom: 15px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: var(--text-light); margin-bottom: 6px;">
                    Alasan Pembatalan <span style="color: #f87171;">* (Wajib Diisi karena pesanan sudah dibayar)</span>
                </label>
                <textarea name="cancellation_reason" id="cancellationReasonInput" rows="3" style="width: 100%; padding: 10px; background: #111; border: 1px solid #333; border-radius: 4px; color: #fff; font-size: 13px; box-sizing: border-box;" placeholder="Contoh: Salah memilih tanggal acara / jadwal photoshoot diundur..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" style="padding: 8px 16px; font-size: 13px;" onclick="closeCancelModal()">Tutup</button>
                <button type="submit" class="btn btn-primary" style="padding: 8px 18px; font-size: 13px; font-weight: 600; background: #b91c1c; border-color: #b91c1c;">
                    <i class="fa-solid fa-ban"></i> Ya, Batalkan Pesanan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openCancelModal(orderId, orderCode, paymentStatus) {
        const form = document.getElementById('cancelOrderForm');
        form.action = `/my-orders/${orderId}/cancel`;

        const desc = document.getElementById('cancelModalDesc');
        const reasonGroup = document.getElementById('reasonInputGroup');
        const reasonInput = document.getElementById('cancellationReasonInput');

        if (paymentStatus === 'paid') {
            desc.innerHTML = `Pesanan <strong>#${orderCode}</strong> telah terbayar (namun belum dikemas). Sesuai kebijakan kami, harap sertakan alasan pembatalan agar tim kami dapat memproses koordinasi pengembalian dana.`;
            reasonGroup.style.display = 'block';
            reasonInput.required = true;
        } else {
            desc.innerHTML = `Pesanan <strong>#${orderCode}</strong> belum dibayar. Pembatalan dapat dilakukan langsung dan kostum akan segera kembali tersedia untuk disewa pelanggan lain.`;
            reasonGroup.style.display = 'none';
            reasonInput.required = false;
        }

        document.getElementById('cancelModal').classList.add('active');
    }

    function closeCancelModal() {
        document.getElementById('cancelModal').classList.remove('active');
    }
</script>
@endsection
