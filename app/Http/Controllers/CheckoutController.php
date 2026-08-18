<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page for the selected product.
     */
    public function index(Product $product)
    {
        // Ensure the product is available for rent
        if ($product->status !== 'available') {
            return redirect()->route('collection')->with('error', 'Maaf, kostum ini sedang disewa atau tidak tersedia saat ini.');
        }

        $user = Auth::user();

        return view('frontend.checkout', compact('product', 'user'));
    }

    /**
     * Process checkout submission, validate inputs, save uploaded files, and persist the order.
     */
    public function process(Request $request, Product $product)
    {
        // 1. Double check product availability
        if ($product->status !== 'available') {
            return redirect()->route('collection')->with('error', 'Maaf, kostum ini sedang tidak tersedia untuk disewa.');
        }

        // 2. Comprehensive validation
        $validated = $request->validate([
            'foto_ktp' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'foto_selfie' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:3072'],
            'nik' => ['required', 'string', 'max:30'],
            'name' => ['required', 'string', 'max:255'],
            'ttl' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'string', 'max:50'],
            'delivery_method' => ['required', 'in:pickup,delivery'],
            'whatsapp' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:25'],
            'address' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:qris,dana,ovo,cod'],
            'rental_days' => ['nullable', 'integer', 'min:1', 'max:30'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'foto_ktp.required' => 'Foto KTP wajib diunggah.',
            'foto_ktp.image' => 'File KTP harus berupa gambar (JPG, PNG).',
            'foto_ktp.max' => 'Ukuran foto KTP maksimal 3MB.',
            'foto_selfie.required' => 'Foto selfie dengan KTP wajib diunggah.',
            'foto_selfie.image' => 'File selfie harus berupa gambar (JPG, PNG).',
            'foto_selfie.max' => 'Ukuran foto selfie maksimal 3MB.',
            'nik.required' => 'NIK KTP wajib diisi.',
            'name.required' => 'Nama lengkap sesuai KTP wajib diisi.',
            'delivery_method.required' => 'Pilih metode pengambilan barang.',
            'whatsapp.required_if' => 'Nomor WhatsApp wajib diisi jika memilih pengantaran ke rumah.',
            'address.required_if' => 'Alamat pengiriman wajib diisi jika memilih pengantaran ke rumah.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        // 3. File storage & Database transaction
        $order = DB::transaction(function () use ($request, $product) {
            // Save uploaded images to storage/app/public/orders/...
            $ktpPath = $request->file('foto_ktp')->store('orders/ktp', 'public');
            $selfiePath = $request->file('foto_selfie')->store('orders/selfie', 'public');

            $rentalDays = (int) $request->input('rental_days', 1);
            if ($rentalDays < 1) {
                $rentalDays = 1;
            }

            $pricePerDay = $product->price_per_day;
            $totalAmount = $pricePerDay * $rentalDays;

            // Generate a secure unique order code: GTC-YYYYMMDD-XXXXX
            $orderCode = 'GTC-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            return Order::create([
                'order_code' => $orderCode,
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'nik' => $request->nik,
                'id_card_name' => $request->name,
                'birth_date_place' => $request->ttl,
                'gender' => $request->gender,
                'id_card_path' => $ktpPath,
                'selfie_path' => $selfiePath,
                'delivery_method' => $request->delivery_method,
                'whatsapp' => $request->whatsapp,
                'shipping_address' => $request->address,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'price_per_day' => $pricePerDay,
                'rental_days' => $rentalDays,
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);
        });

        return redirect()->route('checkout.success', $order->order_code)
            ->with('success', 'Pesanan berhasil dibuat! Silakan selesaikan pembayaran.');
    }

    /**
     * Display order invoice and payment instructions.
     */
    public function success(string $order_code)
    {
        $order = Order::with(['product', 'product.images', 'user'])
            ->where('order_code', $order_code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('frontend.success', compact('order'));
    }
}
