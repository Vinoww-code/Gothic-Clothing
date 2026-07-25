@extends('layouts.frontend')

@section('content')
<div class="container" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; margin-top: 40px; margin-bottom: 40px;">
    <div style="background: #111; padding: 30px; border-radius: 8px; text-align: center; border: 1px solid #333; max-width: 500px; width: 100%;">
        
        <h2 style="color: #fff; margin-bottom: 20px; font-family: serif; border-bottom: 1px solid #333; padding-bottom: 15px;">DETAIL PEMBAYARAN</h2>
        
        <!-- DATA PEMBELIAN -->
        <div style="text-align: left; background: #0a0a0a; padding: 15px; border-radius: 6px; margin-bottom: 25px; border-left: 4px solid #8b0000;">
            <p style="margin: 5px 0; color: #ccc; font-size: 14px;"><strong>No. Pesanan:</strong> <span style="color: #fff;">{{ session('unique_code', 'GTC-8891') }}</span></p>
            <p style="margin: 5px 0; color: #ccc; font-size: 14px;"><strong>Produk:</strong> <span style="color: #fff;">{{ session('product_name', 'Set Korset Asimetris Gothic') }}</span></p>
            <p style="margin: 5px 0; color: #ccc; font-size: 14px;"><strong>Metode Ambil:</strong> <span style="color: #fff;">{{ session('delivery_method') == 'pickup' ? 'Ambil di Toko' : 'Kirim ke Alamat' }}</span></p>
            <h4 style="margin: 15px 0 0 0; color: #ff4444; font-size: 18px;">Total Tagihan: Rp{{ number_format(session('total_price', 100000), 0, ',', '.') }}</h4>
        </div>

        <!-- INSTRUKSI PEMBAYARAN BERDASARKAN PILIHAN -->
        @if(session('payment_method') == 'qris')
            <p style="color: #aaa; margin-bottom: 15px; font-size: 14px;">Silakan <i>scan</i> QRIS di bawah ini untuk menyelesaikan pembayaran. Pesanan Anda akan diproses setelah pembayaran berhasil.</p>
            <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QRIS" style="width: 200px; height: 200px; background: white; padding: 10px; border-radius: 8px; margin-bottom: 20px;">
            
        @elseif(session('payment_method') == 'cod')
            <p style="color: #aaa; margin-bottom: 15px; font-size: 14px;">
                Anda memilih <strong>Bayar di Tempat (COD)</strong>.<br>
                @if(session('delivery_method') == 'pickup')
                    Silakan tunjukkan KODE PESANAN di atas kepada kasir kami dan lakukan pembayaran langsung di toko.
                @else
                    Siapkan uang pas senilai total tagihan. Kurir kami akan menagihnya saat kostum sampai di tempat Anda.
                @endif
            </p>

        @elseif(session('payment_method') == 'dana' || session('payment_method') == 'ovo')
            <p style="color: #aaa; margin-bottom: 15px; font-size: 14px;">Silakan transfer sebesar total tagihan ke nomor <strong>{{ strtoupper(session('payment_method')) }}</strong> berikut:</p>
            <div style="background: #000; border: 1px solid #444; padding: 15px; font-size: 20px; font-weight: bold; color: #fff; margin-bottom: 20px;">
                {{ session('payment_method') == 'dana' ? '0812-3456-7890 (DANA)' : '0898-7654-3210 (OVO)' }} <br>
                <span style="font-size: 14px; color: #888; font-weight: normal;">a.n. GOTHIC CLOTHING</span>
            </div>
            
        @else
            <!-- Default jika sesi kosong tapi halaman dibuka langsung -->
            <p style="color: #aaa; margin-bottom: 20px;">Menunggu pembayaran diselesaikan...</p>
        @endif

        <a href="{{ url('/') }}" style="display: block; width: 100%; padding: 12px; background: #8b0000; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 15px; box-sizing: border-box;">SAYA SUDAH BAYAR / SELESAI</a>
    </div>
</div>
@endsection