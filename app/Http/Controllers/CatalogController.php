<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    public function index(Request $request, $type = 'collection')
    {
        // 1. Mulai Query
        $query = Product::with('images', 'category');

        // 2. Tentukan Tipe Halaman (Costume atau Accessory) DULU
        if ($type === 'accessories') {
            $categories = Category::where('type', 'accessory')->get();
            $query->whereHas('category', function($q) {
                $q->where('type', 'accessory');
            });
            $pageTitle = 'ACCESSORIES';
            $pageSubtitle = 'Temukan berbagai koleksi aksesoris gothic premium untuk melengkapi penampilan Anda.';
        } else {
            $categories = Category::where('type', 'costume')->get();
            $query->whereHas('category', function($q) {
                $q->where('type', 'costume');
            });
            $pageTitle = 'GOTHIC COSTUME';
            $pageSubtitle = 'Temukan berbagai koleksi kostum gothic premium untuk segala kebutuhan acara Anda.';
        }

        if ($request->filled('search')) {
                    $query->where('name', 'like', '%' . $request->search . '%');
                }

                // 3. Masukkan Logika Filter Ukuran (Jika pembeli menceklis)
                if ($request->has('sizes') && is_array($request->sizes)) {
                    $query->where(function($q) use ($request) {
                        foreach ($request->sizes as $size) {
                            $q->orWhereJsonContains('sizes', $size);
                        }
                    });
                }

                // 4. Masukkan Logika Filter Warna (Jika pembeli menceklis)
                if ($request->has('colors') && is_array($request->colors)) {
                    $query->where(function($q) use ($request) {
                        foreach ($request->colors as $color) {
                            $q->orWhereJsonContains('colors', $color);
                        }
                    });
                }
                
                if ($request->has('max_price') && $request->max_price != '') {
                    $query->where('price_per_day', '<=', $request->max_price);
                }
                
        $products = $query->latest()->paginate(12)->withQueryString();
        
        $breadcrumb = ucfirst($type);

        return view('frontend.catalog', compact('categories', 'products', 'pageTitle', 'pageSubtitle', 'breadcrumb', 'type'));
    }

    /**
     * Display a dedicated gothic product detail page.
     */
    public function show(string $slug)
    {
        $product = Product::with(['images', 'category'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Get related products from the same category or latest items
        $relatedProducts = Product::with(['images', 'category'])
            ->where('id', '!=', $product->id)
            ->where('category_id', $product->category_id)
            ->latest()
            ->take(4)
            ->get();

        if ($relatedProducts->isEmpty()) {
            $relatedProducts = Product::with(['images', 'category'])
                ->where('id', '!=', $product->id)
                ->latest()
                ->take(4)
                ->get();
        }

        return view('frontend.product-detail', compact('product', 'relatedProducts'));
    }
}