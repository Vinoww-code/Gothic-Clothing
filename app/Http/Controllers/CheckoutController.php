<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    // 1. Menampilkan Halaman Form Checkout
    public function index(Product $product)
    {
        return view('frontend.checkout', compact('product'));
    }

    // 2. Memproses Data Checkout Bohongan
    public function process(Request $request, Product $product)
    {
        $request->validate([
            'nama' => 'required|string',
            'metode_pengiriman' => 'required|in:pickup,delivery'
        ]);

        // Buat Kode Unik jika dia milih "Ambil di Tempat"
        $kode_pengambilan = null;
        if ($request->metode_pengiriman == 'pickup') {
            $kode_pengambilan = 'GOTHIC-' . strtoupper(Str::random(5)); 
        }

        // Kumpulkan data pesanan (tanpa simpan ke database)
        $orderData = [
            'order_id' => 'ORD-' . time(),
            'nama_pembeli' => $request->nama,
            'produk' => $product->name,
            'harga' => $product->price_per_day,
            'metode' => $request->metode_pengiriman,
            'kode_pengambilan' => $kode_pengambilan,
            'tanggal' => now()->format('d M Y H:i')
        ];

        // Simpan sementara di Session lalu alihkan ke halaman sukses
        return redirect()->route('checkout.success')->with('orderData', $orderData);
    }

    // 3. Menampilkan Halaman Nota/Success
    public function success()
    {
        // Ambil data dari session, jika tidak ada (akses langsung) kembalikan ke home
        $orderData = session('orderData');
        
        if (!$orderData) {
            return redirect('/');
        }

        return view('frontend.success', compact('orderData'));
    }
}