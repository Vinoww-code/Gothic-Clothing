<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    /**
     * Display a listing of orders for the currently authenticated user.
     */
    public function index()
    {
        $orders = Order::with(['product.images', 'product.category'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('frontend.my-orders', compact('orders'));
    }

    /**
     * Cancel an order by customer.
     * Allowed only if order is 'pending' or 'confirmed' (not yet packing/shipping/rented/completed).
     */
    public function cancel(Request $request, Order $order)
    {
        // 1. Authorization check
        if (Auth::id() !== $order->user_id) {
            abort(403, 'Akses ditolak. Ini bukan pesanan Anda.');
        }

        // 2. Check cancellable status
        if (!$order->isCancellable()) {
            return back()->with('error', 'Maaf, pesanan ini sudah dalam proses pengemasan/pengiriman atau telah selesai, sehingga tidak dapat dibatalkan.');
        }

        // 3. Validation: If already paid, reason is mandatory
        if ($order->payment_status === 'paid') {
            $request->validate([
                'cancellation_reason' => ['required', 'string', 'min:5', 'max:500'],
            ], [
                'cancellation_reason.required' => 'Karena pesanan sudah terbayar, harap berikan alasan pembatalan yang jelas.',
                'cancellation_reason.min' => 'Alasan pembatalan minimal 5 karakter.',
                'cancellation_reason.max' => 'Alasan pembatalan maksimal 500 karakter.',
            ]);
            $reason = $request->input('cancellation_reason');
        } else {
            $reason = $request->filled('cancellation_reason') 
                ? $request->input('cancellation_reason') 
                : 'Dibatalkan oleh pelanggan (belum dibayar)';
        }

        // 4. Update order status
        $order->update([
            'order_status' => 'cancelled',
            'cancellation_reason' => $reason,
            'cancelled_at' => now(),
        ]);

        // 5. Automatically restore product availability
        if ($order->product) {
            $order->product->update(['status' => 'available']);
        }

        return redirect()->route('my.orders')
            ->with('success', 'Pesanan #' . $order->order_code . ' berhasil dibatalkan. Kostum telah kembali tersedia di katalog.');
    }
}
