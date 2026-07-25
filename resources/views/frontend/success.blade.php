@extends('layouts.frontend')

@section('content')
<div class="container" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; margin-top: 40px; margin-bottom: 40px;">
    <div style="background: #111; padding: 40px; border-radius: 8px; text-align: center; border: 1px solid #333; max-width: 500px; width: 100%;">
        
        <i class="fa-solid fa-circle-check" style="font-size: 60px; color: #28a745; margin-bottom: 20px;"></i>
        <h2 style="color: #fff; margin-bottom: 10px;">Pemesanan Berhasil Dibuat!</h2>
        
        <!-- PENGATURAN INSTRUKSI PEMBAYARAN -->
        @if(session('payment_method') == 'cod')
            <p style="color: #aaa; margin-bottom: 20px; line-height: 1.6;">
                Anda memilih metode <strong>Bayar di Tempat</strong>. <br>
                @if(session('delivery_method') == 'pickup')
                    Silakan datang ke toko kami, bayar di kasir, dan tunjukkan kode berikut:
                @else
                    Siapkan uang pas. Kostum akan segera kami antar, admin kami akan menghubungi Anda via WhatsApp. Berikut kode resi Anda:
                @endif
            </p>
            <div style="background: #0a0a0a; border: 2px dashed #8b0000; padding: 15px; font-size: 24px; font-weight: bold; color: #ff4444; letter-spacing: 3px; margin-bottom: 20px;">
                {{ session('unique_code') ?? 'GTC-8891' }}
            </div>

        @elseif(session('payment_method') == 'qris')
            <p style="color: #aaa; margin-bottom: 15px; line-height: 1.6;">Silakan <i>scan</i> QRIS di bawah ini untuk menyelesaikan pembayaran menggunakan <i>M-Banking</i> atau <i>E-Wallet</i> Anda:</p>
            <!-- Contoh dummy QR Code -->
            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS" style="width: 200px; height: 200px; background: white; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            <p style="color: #ff4444; font-size: 14px; font-weight: bold;">KODE PESANAN: {{ session('unique_code') }}</p>

        @elseif(session('payment_method') == 'dana' || session('payment_method') == 'ovo')
            <p style="color: #aaa; margin-bottom: 20px; line-height: 1.6;">Silakan transfer pembayaran ke nomor {{ strtoupper(session('payment_method')) }} berikut:</p>
            <div style="background: #0a0a0a; border: 1px solid #333; padding: 15px; font-size: 20px; font-weight: bold; color: #fff; margin-bottom: 20px;">
                {{ session('payment_method') == 'dana' ? '0812-3456-7890 (DANA)' : '0898-7654-3210 (OVO)' }} <br>
                <span style="font-size: 14px; color: #888; font-weight: normal;">a.n. GOTHIC CLOTHING</span>
            </div>
            <p style="color: #ff4444; font-size: 14px; font-weight: bold;">KODE PESANAN: {{ session('unique_code') }}</p>
        @else
            <!-- Jika tidak ada session (halaman dibuka langsung) -->
            <p style="color: #aaa; margin-bottom: 20px;">Sesi pembayaran tidak ditemukan atau sudah kadaluarsa.</p>
        @endif

        <a href="{{ url('/') }}" style="display: inline-block; padding: 12px 25px; background: #8b0000; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 10px;">SELESAI & KEMBALI</a>
    </div>
</div>
@endsection