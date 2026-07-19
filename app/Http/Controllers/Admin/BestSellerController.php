<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class BestSellerController extends Controller
{
    public function index()
    {
        // Tampilkan produk yang sudah best seller di atas, sisanya di bawah
        $products = Product::with('category', 'images')
                            ->orderBy('is_best_seller', 'desc')
                            ->latest()
                            ->get();
                            
        return view('admin.bestsellers.index', compact('products'));
    }

    public function toggle($id)
    {
        $product = Product::findOrFail($id);
        
        // Balikkan status: jika true jadi false, jika false jadi true
        $product->update([
            'is_best_seller' => !$product->is_best_seller
        ]);

        return back();
    }
}