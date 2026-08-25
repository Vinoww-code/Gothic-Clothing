<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        $stats = [
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'month_revenue' => Order::where('payment_status', 'paid')
                ->whereYear('created_at', $now->year)
                ->whereMonth('created_at', $now->month)
                ->sum('total_amount'),
            'pending_orders' => Order::where('order_status', 'pending')->count(),
            'pending_payments' => Order::where('payment_status', 'pending')->count(),
            'total_products' => Product::count(),
            'available_products' => Product::where('status', 'available')->count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),
        ];

        $recentOrders = Order::with(['product', 'user'])
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}