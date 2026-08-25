<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    /**
     * Display comprehensive financial statistics and transaction logs.
     */
    public function index(Request $request)
    {
        $now = Carbon::now();

        // 1. Overall Revenue Summary
        $totalPaidRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalPendingRevenue = Order::where('payment_status', 'pending')->sum('total_amount');
        $totalFailedRevenue = Order::whereIn('payment_status', ['failed'])->orWhere('order_status', 'cancelled')->sum('total_amount');
        
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        $thisMonthRevenue = Order::where('payment_status', 'paid')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('total_amount');

        $thisYearRevenue = Order::where('payment_status', 'paid')
            ->whereYear('created_at', $now->year)
            ->sum('total_amount');

        $totalTransactionsCount = Order::count();
        $paidTransactionsCount = Order::where('payment_status', 'paid')->count();
        $pendingTransactionsCount = Order::where('payment_status', 'pending')->count();

        // 2. Revenue Breakdown by Payment Method
        $paymentMethods = ['qris' => 'QRIS', 'dana' => 'DANA', 'ovo' => 'OVO', 'cod' => 'COD (Cash On Delivery)'];
        $paymentBreakdown = [];
        foreach ($paymentMethods as $key => $label) {
            $paidSum = Order::where('payment_method', $key)->where('payment_status', 'paid')->sum('total_amount');
            $totalCount = Order::where('payment_method', $key)->count();
            $paymentBreakdown[$key] = [
                'label' => $label,
                'total_amount' => $paidSum,
                'count' => $totalCount,
                'percentage' => $totalPaidRevenue > 0 ? round(($paidSum / $totalPaidRevenue) * 100, 1) : 0,
            ];
        }

        // 3. Top Earning Products
        $topProducts = Order::where('payment_status', 'paid')
            ->select('product_id', DB::raw('count(*) as total_rentals'), DB::raw('sum(total_amount) as revenue'))
            ->groupBy('product_id')
            ->with('product')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        // 4. Transaction List Query with Filters
        $query = Order::with(['product', 'user'])->latest();

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $transactions = $query->paginate(15)->withQueryString();

        return view('admin.finance.index', compact(
            'totalPaidRevenue',
            'totalPendingRevenue',
            'totalFailedRevenue',
            'todayRevenue',
            'thisMonthRevenue',
            'thisYearRevenue',
            'totalTransactionsCount',
            'paidTransactionsCount',
            'pendingTransactionsCount',
            'paymentBreakdown',
            'topProducts',
            'transactions'
        ));
    }
}
