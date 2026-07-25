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

public function process(Request $request, $id)
    {
        // 1. Validasi sederhana
        $request->validate([
            'foto_ktp' => 'required|image|max:3000',
            'foto_selfie' => 'required|image|max:3000',
            'delivery_method' => 'required',
            'payment_method' => 'required',
        ]);

        // 2. Ambil data produk untuk ditampilkan di tagihan
        $product = Product::findOrFail($id);

        // 3. Buat kode transaksi unik
        $kode_unik = 'GTC-' . strtoupper(Str::random(5));

        // 4. Langsung lemparkan ke halaman sukses beserta SEMUA DATA PESANAN!
        return redirect()->route('checkout.success')->with([
            'product_name' => $product->name,
            'total_price' => $product->price_per_day,
            'delivery_method' => $request->delivery_method,
            'payment_method' => $request->payment_method,
            'unique_code' => $kode_unik
        ]);
    }

    public function success()
    {
        // HAPUS fitur yang menendang ke Home agar kamu tidak error lagi
        return view('frontend.success');
    }

    }
