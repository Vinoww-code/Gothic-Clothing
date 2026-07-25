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
            // 1. Validasi sederhana saja yang penting gambar terisi
            $request->validate([
                'foto_ktp' => 'required|image|max:3000',
                'foto_selfie' => 'required|image|max:3000',
                'delivery_method' => 'required',
                'payment_method' => 'required',
            ]);

            // 2. Simpan foto ke folder public (opsional, jika ingin dilihat nanti)
            if($request->hasFile('foto_ktp')) {
                $request->file('foto_ktp')->store('ktp_images', 'public');
            }
            if($request->hasFile('foto_selfie')) {
                $request->file('foto_selfie')->store('selfie_images', 'public');
            }

            // 3. Buat kode transaksi unik
            $kode_unik = 'GTC-' . strtoupper(Str::random(5));

            // 4. Langsung lemparkan ke halaman sukses!
            return redirect()->route('checkout.success')->with([
                'delivery_method' => $request->delivery_method,
                'payment_method' => $request->payment_method,
                'unique_code' => $kode_unik
            ]);
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