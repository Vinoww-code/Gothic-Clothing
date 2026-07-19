@extends('layouts.frontend')

@section('content')
<div class="container" style="margin-top: 50px; margin-bottom: 100px; color: white;">
    <div class="row justify-content-center">
        <div class="col-md-6" style="background: #111; padding: 30px; border-radius: 8px; border: 1px solid #333;">
            <h2 style="font-family: serif; color: #8b0000; text-align: center; margin-bottom: 30px;">Formulir Sewa</h2>
            
            <div style="background: #222; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <h5>Produk: {{ $product->name }}</h5>
                <p class="mb-0 text-muted">Harga: Rp{{ number_format($product->price_per_day, 0, ',', '.') }} / hari</p>
            </div>

            <form action="{{ route('checkout.process', $product->id) }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" style="background: #222; color: white; border: 1px solid #444;" required>
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Metode Pengiriman</label>
                    <select name="metode_pengiriman" class="form-select" style="background: #222; color: white; border: 1px solid #444;" required>
                        <option value="delivery">Kirim ke Lokasi (Delivery)</option>
                        <option value="pickup">Ambil Sendiri di Toko (Pickup)</option>
                    </select>
                </div>

                <button type="submit" class="btn w-100" style="background: #8b0000; color: white; font-weight: bold; padding: 12px;">
                    BAYAR SEKARANG (BOHONGAN)
                </button>
            </form>
        </div>
    </div>
</div>
@endsection