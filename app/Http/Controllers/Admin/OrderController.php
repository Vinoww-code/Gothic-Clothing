<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of orders with filters and search.
     */
    public function index(Request $request)
    {
        $query = Order::with(['product', 'user'])->latest();

        // 1. Filter by Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // 2. Filter by Order Status
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        // 3. Filter by Delivery Method
        if ($request->filled('delivery_method')) {
            $query->where('delivery_method', $request->delivery_method);
        }

        // 4. Search by Order Code, Customer Name, or WhatsApp
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('id_card_name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15)->withQueryString();

        // Status counters for quick filter tabs
        $counts = [
            'all' => Order::count(),
            'pending_payment' => Order::where('payment_status', 'pending')->count(),
            'paid' => Order::where('payment_status', 'paid')->count(),
            'pending_order' => Order::where('order_status', 'pending')->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->count(),
            'packing' => Order::where('order_status', 'packing')->count(),
            'shipping' => Order::where('order_status', 'shipping')->count(),
            'rented' => Order::where('order_status', 'rented')->count(),
            'completed' => Order::where('order_status', 'completed')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }

    /**
     * Display the specified order details.
     */
    public function show(Order $order)
    {
        $order->load(['product.images', 'product.category', 'user']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order and payment status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:pending,paid,failed'],
            'order_status' => ['required', 'in:pending,confirmed,packing,shipping,rented,completed,cancelled'],
            'admin_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order->update([
            'payment_status' => $validated['payment_status'],
            'order_status' => $validated['order_status'],
            'notes' => $validated['admin_notes'] ? ($order->notes ? $order->notes . "\n[Admin Note]: " . $validated['admin_notes'] : "[Admin Note]: " . $validated['admin_notes']) : $order->notes,
        ]);

        // Otomatis sinkronkan status produk
        if (in_array($validated['order_status'], ['confirmed', 'packing', 'shipping', 'rented'])) {
            $order->product()->update(['status' => 'rented']);
        } elseif (in_array($validated['order_status'], ['completed', 'cancelled'])) {
            $order->product()->update(['status' => 'available']);
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Status pesanan #' . $order->order_code . ' berhasil diperbarui!');
    }
}
