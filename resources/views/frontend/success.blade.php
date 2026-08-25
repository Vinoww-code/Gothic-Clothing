@extends('layouts.frontend')

@section('content')
<style>
    .invoice-wrapper {
        max-width: 650px;
        margin: 40px auto;
        background: #111;
        padding: 35px;
        border-radius: 10px;
        border: 1px solid #2a2a2a;
        color: #fff;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
    }

    .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: bold;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        transition: all 0.3s ease;
    }

    .status-pending { background: rgba(255, 193, 7, 0.2); color: #ffc107; border: 1px solid #ffc107; }
    .status-paid { background: rgba(25, 135, 84, 0.2); color: #2ecc71; border: 1px solid #2ecc71; }
    .status-failed { background: rgba(220, 53, 69, 0.2); color: #e74c3c; border: 1px solid #e74c3c; }
    .status-confirmed { background: rgba(13, 110, 253, 0.2); color: #3498db; border: 1px solid #3498db; }
    .status-processing { background: rgba(23, 162, 184, 0.2); color: #17a2b8; border: 1px solid #17a2b8; }
    .status-completed { background: rgba(40, 167, 69, 0.2); color: #28a745; border: 1px solid #28a745; }
    .status-cancelled { background: rgba(108, 117, 125, 0.2); color: #6c757d; border: 1px solid #6c757d; }

    .detail-card {
        background: #0a0a0a;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #222;
        margin-bottom: 20px;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid #1a1a1a;
        font-size: 14px;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .copy-btn {
        background: #222;
        border: 1px solid #444;
        color: #ddd;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        margin-left: 8px;
        transition: 0.2s;
    }

    .copy-btn:hover {
        background: #8b0000;
        border-color: #8b0000;
        color: #fff;
    }

    .live-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        color: #4ade80;
        background: rgba(74, 222, 128, 0.1);
        padding: 3px 8px;
        border-radius: 12px;
        border: 1px solid rgba(74, 222, 128, 0.3);
    }

    .live-dot {
        width: 7px;
        height: 7px;
        background-color: #4ade80;
        border-radius: 50%;
        box-shadow: 0 0 8px #4ade80;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); opacity: 0.7; }
        50% { transform: scale(1.3); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.7; }
    }
</style>

<div class="container" style="min-height: 70vh; margin-top: 20px; margin-bottom: 50px;">
    <div class="invoice-wrapper">
        
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 25px; border-bottom: 1px solid #2a2a2a; padding-bottom: 20px;">
            <div style="width: 50px; height: 50px; background: rgba(25, 135, 84, 0.15); border: 2px solid #198754; color: #198754; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 12px;">
                <i class="fa-solid fa-check"></i>
            </div>
            <h2 style="margin: 0 0 5px 0; font-family: serif; color: #fff;">PESANAN BERHASIL DICATAT</h2>
            <p style="margin: 0 0 10px 0; color: #aaa; font-size: 13px;">Silakan selesaikan pembayaran sesuai instruksi di bawah ini.</p>
            <div class="live-indicator">
                <span class="live-dot"></span> Realtime Live Tracking
            </div>
        </div>

        <!-- Order Information Summary -->
        <div class="detail-card">
            <div class="detail-row">
                <span style="color: #888;">No. Pesanan</span>
                <span style="font-weight: bold; color: #ff6b6b;">
                    <span id="orderCodeText">{{ $order->order_code }}</span>
                    <button type="button" class="copy-btn" onclick="copyOrderCode()"><i class="fa-regular fa-copy"></i> Salin</button>
                </span>
            </div>
            <div class="detail-row">
                <span style="color: #888;">Tanggal Pesanan</span>
                <span style="color: #ddd;">{{ $order->created_at ? $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' : now()->timezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB' }}</span>
            </div>
            <div class="detail-row">
                <span style="color: #888;">Status Pembayaran</span>
                <span id="paymentStatusBadge" class="badge-status status-{{ strtolower($order->payment_status ?? 'pending') }}">
                    {{ strtoupper($order->payment_status ?? 'pending') }}
                </span>
            </div>
            <div class="detail-row">
                <span style="color: #888;">Status Pesanan</span>
                <span id="orderStatusBadge" class="badge-status status-{{ strtolower($order->order_status ?? 'pending') }}">
                    {{ strtoupper($order->order_status ?? 'pending') }}
                </span>
            </div>
        </div>

        <!-- Item & Billing Details -->
        <div class="detail-card">
            <h4 style="margin: 0 0 15px 0; color: #fff; font-size: 15px; border-bottom: 1px solid #1a1a1a; padding-bottom: 8px;">
                <i class="fa-solid fa-shirt" style="margin-right: 6px; color: #8b0000;"></i> Rincian Produk Sewa
            </h4>
            <div style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px;">
                @if($order->product && $order->product->images && $order->product->images->count() > 0)
                    <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" alt="{{ $order->product->name }}" style="width: 60px; height: 60px; border-radius: 6px; object-fit: cover; border: 1px solid #333;">
                @else
                    <div style="width: 60px; height: 60px; background: #222; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #666;"><i class="fa-solid fa-shirt"></i></div>
                @endif
                <div style="flex: 1;">
                    <div style="font-weight: bold; color: #fff; font-size: 15px;">{{ $order->product->name ?? 'Produk Sewa' }}</div>
                    <div style="color: #888; font-size: 13px;">Tarif: Rp{{ number_format($order->price_per_day, 0, ',', '.') }} / hari</div>
                </div>
            </div>

            <div class="detail-row">
                <span style="color: #888;">Periode Sewa</span>
                <span style="color: #ddd;">
                    @if($order->start_date && $order->end_date)
                        {{ $order->start_date->format('d M Y') }} s/d {{ $order->end_date->format('d M Y') }} ({{ $order->rental_days }} Hari)
                    @else
                        {{ $order->rental_days }} Hari
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span style="color: #888;">Metode Pengambilan</span>
                <span style="color: #ddd;">{{ $order->delivery_method === 'pickup' ? 'Ambil di Toko / Butik' : 'Diantar ke Rumah' }}</span>
            </div>
            @if($order->delivery_method === 'delivery')
                <div class="detail-row">
                    <span style="color: #888;">WhatsApp</span>
                    <span style="color: #ddd;">{{ $order->whatsapp }}</span>
                </div>
                <div class="detail-row" style="flex-direction: column; align-items: flex-start; gap: 4px;">
                    <span style="color: #888;">Alamat Pengantaran</span>
                    <span style="color: #ccc; font-size: 13px;">{{ $order->shipping_address }}</span>
                </div>
            @endif
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; padding-top: 12px; border-top: 2px solid #222;">
                <span style="font-size: 16px; font-weight: bold; color: #fff;">Total Tagihan:</span>
                <span style="font-size: 20px; font-weight: bold; color: #ff4444;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Instructions Box -->
        <div id="paymentBox" style="background: #0a0a0a; border: 1px solid #333; border-radius: 8px; padding: 25px; text-align: center; margin-bottom: 25px;">
            <h4 style="margin: 0 0 15px 0; color: #fff; font-size: 16px;">
                <i class="fa-solid fa-credit-card" style="margin-right: 6px; color: #8b0000;"></i> Instruksi Pembayaran ({{ strtoupper($order->payment_method) }})
            </h4>

            @if($order->payment_method === 'qris')
                <p style="color: #aaa; font-size: 13px; margin-bottom: 15px;">
                    Silakan scan QRIS di bawah ini melalui aplikasi BCA Mobile, GoPay, OVO, DANA, ShopeePay, atau m-Banking Anda:
                </p>
                <div style="display: inline-block; background: #fff; padding: 15px; border-radius: 8px; margin-bottom: 15px;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS Code" style="width: 180px; height: 180px; display: block;">
                </div>
                <div style="color: #ccc; font-size: 13px;">NMID: ID102003004005 | <strong>GOTHIC CLOTHING STORE</strong></div>

            @elseif($order->payment_method === 'dana' || $order->payment_method === 'ovo')
                <p style="color: #aaa; font-size: 13px; margin-bottom: 15px;">
                    Silakan transfer sebesar <strong style="color: #ff4444;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong> ke nomor e-wallet resmi kami:
                </p>
                <div style="background: #000; border: 1px solid #444; padding: 15px; border-radius: 6px; margin-bottom: 15px;">
                    <div style="font-size: 22px; font-weight: bold; color: #fff; letter-spacing: 1px;">
                        {{ $order->payment_method === 'dana' ? '0812-3456-7890' : '0898-7654-3210' }}
                    </div>
                    <div style="color: #888; font-size: 13px; margin-top: 4px;">a.n. <strong>GOTHIC CLOTHING OFFICIAL</strong></div>
                </div>
                <p style="color: #777; font-size: 12px; margin: 0;">Sertakan catatan transfer: <strong>{{ $order->order_code }}</strong></p>

            @elseif($order->payment_method === 'cod')
                <div style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.3); padding: 15px; border-radius: 6px; text-align: left; font-size: 13px; color: #ccc;">
                    <strong style="color: #ffc107;"><i class="fa-solid fa-handshake"></i> Bayar di Tempat (COD)</strong>
                    <p style="margin: 8px 0 0 0; line-height: 1.5;">
                        @if($order->delivery_method === 'pickup')
                            Pesanan Anda telah dicatat. Tunjukkan kode pesanan <strong>{{ $order->order_code }}</strong> saat mengambil kostum di butik kami untuk melakukan pembayaran tunai atau debit di kasir.
                        @else
                            Kurir kami akan menghubungi via WhatsApp ({{ $order->whatsapp }}) sebelum pengantaran. Mohon siapkan uang pas sebesar <strong style="color:#ff4444;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</strong> saat kostum tiba.
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 12px; flex-direction: column;">
            <a href="{{ route('my.orders') }}" style="display: block; text-align: center; width: 100%; box-sizing: border-box; padding: 14px; background: #8b0000; color: #fff; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; letter-spacing: 0.5px; transition: 0.3s;" onmouseover="this.style.background='#a10000'" onmouseout="this.style.background='#8b0000'">
                <i class="fa-solid fa-clock-rotate-left" style="margin-right: 6px;"></i> LIHAT RIWAYAT PESANAN SAYA
            </a>

            @if($order->isCancellable())
                <button type="button" onclick="openCancelModal()" style="display: block; text-align: center; width: 100%; box-sizing: border-box; padding: 12px; background: transparent; border: 1px solid #dc2626; color: #f87171; border-radius: 6px; font-weight: bold; font-size: 14px; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#dc2626'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='#f87171';">
                    <i class="fa-solid fa-ban" style="margin-right: 6px;"></i> BATALKAN PESANAN INI
                </button>
            @endif

            <a href="{{ route('collection') }}" style="display: block; text-align: center; width: 100%; box-sizing: border-box; padding: 12px; background: transparent; border: 1px solid #444; color: #cbd5e1; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 14px; transition: 0.3s;" onmouseover="this.style.borderColor='#888'; this.style.color='#fff';" onmouseout="this.style.borderColor='#444'; this.style.color='#cbd5e1';">
                <i class="fa-solid fa-shirt" style="margin-right: 6px;"></i> JELAJAHI KOLEKSI LAINNYA
            </a>
            <a href="{{ route('home') }}" style="display: block; text-align: center; width: 100%; box-sizing: border-box; padding: 10px; background: transparent; border: none; color: #777; text-decoration: none; font-size: 13px; transition: 0.3s;" onmouseover="this.style.color='#aaa';" onmouseout="this.style.color='#777';">
                Kembali ke Beranda
            </a>
        </div>

        @if($order->isCancellable())
            <!-- Modal Pembatalan -->
            <div id="cancelSuccessModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; justify-content: center; align-items: center; padding: 20px;">
                <div style="background: #161922; border: 1px solid #334155; border-radius: 8px; width: 100%; max-width: 480px; padding: 25px;" onclick="event.stopPropagation()">
                    <h3 style="margin: 0 0 12px; color: #fff; font-size: 18px;"><i class="fa-solid fa-triangle-exclamation" style="color: #f87171;"></i> Batalkan Pesanan</h3>
                    <p style="color: #cbd5e1; font-size: 14px; line-height: 1.6; margin-bottom: 15px;">
                        @if($order->payment_status === 'paid')
                            Pesanan Anda telah terbayar (belum dikemas). Harap masukkan alasan pembatalan agar tim kami dapat memproses koordinasi pembatalan.
                        @else
                            Apakah Anda yakin ingin membatalkan pesanan ini? Kostum akan segera dilepaskan dan kembali tersedia untuk disewa.
                        @endif
                    </p>

                    <form method="POST" action="{{ route('my.orders.cancel', $order->id) }}">
                        @csrf
                        @if($order->payment_status === 'paid')
                            <div style="margin-bottom: 15px;">
                                <label style="display: block; font-size: 13px; font-weight: 600; color: #cbd5e1; margin-bottom: 6px;">
                                    Alasan Pembatalan <span style="color: #f87171;">* (Wajib Diisi)</span>
                                </label>
                                <textarea name="cancellation_reason" rows="3" required style="width: 100%; padding: 10px; background: #0a0a0a; border: 1px solid #334155; border-radius: 6px; color: #fff; font-size: 13px; box-sizing: border-box;" placeholder="Contoh: Salah pilih ukuran / acara dibatalkan..."></textarea>
                            </div>
                        @endif

                        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                            <button type="button" onclick="closeCancelModal()" style="background: transparent; border: 1px solid #444; color: #cbd5e1; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Tutup</button>
                            <button type="submit" style="background: #dc2626; color: #fff; border: none; padding: 8px 18px; border-radius: 4px; font-weight: bold; cursor: pointer;">
                                <i class="fa-solid fa-ban"></i> Ya, Batalkan Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <script>
                function openCancelModal() {
                    const m = document.getElementById('cancelSuccessModal');
                    if (m) { m.style.display = 'flex'; }
                }
                function closeCancelModal() {
                    const m = document.getElementById('cancelSuccessModal');
                    if (m) { m.style.display = 'none'; }
                }
            </script>
        @endif

    </div>
</div>

<script>
    function copyOrderCode() {
        const text = document.getElementById('orderCodeText').innerText;
        navigator.clipboard.writeText(text).then(() => {
            alert('Kode pesanan ' + text + ' berhasil disalin!');
        });
    }

    // ==========================================
    // REALTIME ORDER STATUS POLLING (Setiap 3 detik)
    // ==========================================
    const orderCode = "{{ $order->order_code }}";
    const statusUrl = "{{ route('checkout.status', $order->order_code) }}";
    let lastPaymentStatus = "{{ $order->payment_status }}";
    let lastOrderStatus = "{{ $order->order_status }}";

    function updateBadge(elementId, statusText, statusClass) {
        const el = document.getElementById(elementId);
        if (el) {
            el.className = 'badge-status ' + statusClass;
            el.innerText = statusText.toUpperCase();
        }
    }

    function checkOrderStatusRealtime() {
        fetch(statusUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network error');
            return response.json();
        })
        .then(data => {
            // Update Payment Status
            if (data.payment_status) {
                const pClass = 'status-' + data.payment_status.toLowerCase();
                updateBadge('paymentStatusBadge', data.payment_status, pClass);
                
                // Jika berubah jadi paid
                if (data.payment_status === 'paid' && lastPaymentStatus !== 'paid') {
                    const payBox = document.getElementById('paymentBox');
                    if (payBox) {
                        payBox.innerHTML = `
                            <div style="background: rgba(25, 135, 84, 0.15); border: 1px solid #198754; padding: 20px; border-radius: 8px; color: #2ecc71;">
                                <i class="fa-solid fa-circle-check" style="font-size: 32px; margin-bottom: 10px;"></i>
                                <h3 style="margin: 0 0 5px 0; color: #fff;">PEMBAYARAN DIVERIFIKASI!</h3>
                                <p style="margin: 0; font-size: 13px; color: #ccc;">Terima kasih, pembayaran Anda telah berhasil kami terima.</p>
                            </div>
                        `;
                    }
                }
                lastPaymentStatus = data.payment_status;
            }

            // Update Order Status
            if (data.order_status) {
                const oClass = 'status-' + data.order_status.toLowerCase();
                updateBadge('orderStatusBadge', data.order_status, oClass);
                lastOrderStatus = data.order_status;
            }
        })
        .catch(err => {
            console.debug('Realtime sync waiting...', err);
        });
    }

    // Polling interval setiap 3 detik secara background
    setInterval(checkOrderStatusRealtime, 3000);
</script>
@endsection