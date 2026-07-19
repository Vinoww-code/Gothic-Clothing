@extends('layouts.frontend')

@section('content')
<div class="container" style="margin-top: 50px; margin-bottom: 100px; color: white;">
    <div class="row justify-content-center">
        <div class="col-md-6" style="background: #111; padding: 40px; border-radius: 8px; border: 1px solid #333; text-align: center;">
            <i class="fa-solid fa-circle-check" style="font-size: 60px; color: #28a745; margin-bottom: 20px;"></i>
            <h2 style="font-family: serif;">Pembayaran Berhasil!</h2>
            <p class="text-muted mb-4">Terima kasih telah menyewa di Gothic Clothing.</p>

            <div style="background: #222; padding: 20px; border-radius: 8px; text-align: left; margin-bottom: 30px; border: 1px dashed #444;">
                <p><strong>Order ID:</strong> {{ $orderData['order_id'] }}</p>
                <p><strong>Nama:</strong> {{ $orderData['nama_pembeli'] }}</p>
                <p><strong>Produk:</strong> {{ $orderData['produk'] }}</p>
                <p><strong>Harga:</strong> Rp{{ number_format($orderData['harga'], 0, ',', '.') }} / hari</p>
                <p><strong>Tanggal:</strong> {{ $orderData['tanggal'] }}</p>
                <hr style="border-color: #444;">
                <p class="mb-0"><strong>Metode:</strong> {{ $orderData['metode'] == 'pickup' ? 'Ambil di Toko' : 'Dikirim ke Lokasi' }}</p>
            </div>

            <!-- JIKA MILIH PICKUP, TAMPILKAN KODE INI -->
            @if($orderData['metode'] == 'pickup')
                <div style="background: rgba(139, 0, 0, 0.2); border: 1px solid #8b0000; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
                    <h5 style="color: #ff4444; margin-bottom: 10px;">KODE PENGAMBILAN ANDA</h5>
                    <h1 style="letter-spacing: 5px; margin-bottom: 0;">{{ $orderData['kode_pengambilan'] }}</h1>
                    <small class="text-muted mt-2 d-block">Tunjukkan kode ini kepada kasir saat mengambil kostum.</small>
                </div>
            @else
                <!-- JIKA MILIH DELIVERY, TAMPILKAN INI -->
                <div style="background: rgba(255, 255, 255, 0.05); padding: 15px; border-radius: 8px; margin-bottom: 30px;">
                    <i class="fa-solid fa-truck-fast mb-2" style="font-size: 24px;"></i>
                    <p class="mb-0 text-muted">Kostum Anda sedang disiapkan dan akan segera dikirimkan ke alamat Anda.</p>
                </div>
            @endif

            <a href="{{ url('/') }}" class="btn btn-outline-light w-100">KEMBALI KE BERANDA</a>
        </div>
    </div>
</div>
@endsection