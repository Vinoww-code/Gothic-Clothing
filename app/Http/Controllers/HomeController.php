<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Faq;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil Banner aktif
        $banner = Banner::where('is_active', true)->latest()->first();
        
        // Ambil 4 Produk Best Seller
        $bestSellers = Product::with('images', 'category')
                              ->where('is_best_seller', true)
                              ->latest()
                              ->take(4)
                              ->get();
                              
        // Ambil 3 Testimoni terbaru
        $testimonials = Testimonial::latest()->take(3)->get();
        
        // Ambil semua FAQ
        $faqs = Faq::latest()->get();

        return view('welcome', compact('banner', 'bestSellers', 'testimonials', 'faqs'));
    }
    public function contact()
    {
        return view('frontend.contact');
    }
}