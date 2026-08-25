<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
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
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'rental_days' => ['nullable', 'integer', 'min:1', 'max:30'],
            'delivery_method' => ['required', 'in:pickup,delivery'],
            'whatsapp' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:25'],
            'address' => ['required_if:delivery_method,delivery', 'nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:qris,dana,ovo,cod'],
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
            'end_date.after_or_equal' => 'Tanggal selesai sewa tidak boleh sebelum tanggal mulai sewa.',
            'delivery_method.required' => 'Pilih metode pengambilan barang.',
            'whatsapp.required_if' => 'Nomor WhatsApp wajib diisi jika memilih pengantaran ke rumah.',
            'address.required_if' => 'Alamat pengiriman wajib diisi jika memilih pengantaran ke rumah.',
            'payment_method.required' => 'Pilih metode pembayaran.',
        ]);

        // 3. File storage & Database transaction
        $order = DB::transaction(function () use ($request, $product) {
            // Save uploaded images to private storage (storage/app/orders/...)
            $ktpPath = $request->file('foto_ktp')->store('orders/ktp', 'local');
            $selfiePath = $request->file('foto_selfie')->store('orders/selfie', 'local');

            $startDate = $request->filled('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::today();
            
            if ($request->filled('end_date')) {
                $endDate = Carbon::parse($request->input('end_date'));
                $rentalDays = max(1, $startDate->diffInDays($endDate) + 1);
            } else {
                $rentalDays = max(1, (int) $request->input('rental_days', 1));
                $endDate = (clone $startDate)->addDays($rentalDays - 1);
            }

            $pricePerDay = $product->price_per_day;
            $totalAmount = $pricePerDay * $rentalDays;

            // Generate a secure unique order code: GTC-YYYYMMDD-XXXXX
            $orderCode = 'GTC-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $order = Order::create([
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
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'total_amount' => $totalAmount,
                'notes' => $request->notes,
            ]);

            // Otomatis ubah status produk menjadi 'rented' (Sedang Disewa)
            $product->update(['status' => 'rented']);

            return $order;
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

    /**
     * Realtime JSON status check for order tracking.
     */
    public function checkStatus(string $order_code)
    {
        $order = Order::where('order_code', $order_code)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return response()->json([
            'order_code' => $order->order_code,
            'payment_status' => $order->payment_status,
            'order_status' => $order->order_status,
            'payment_status_label' => strtoupper($order->payment_status),
            'order_status_label' => strtoupper($order->order_status),
            'updated_at' => $order->updated_at ? $order->updated_at->timezone('Asia/Jakarta')->format('d M Y, H:i:s') : null,
        ]);
    }
}
