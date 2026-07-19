<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->latest()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_per_day' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,rented,maintenance',
            'sizes' => 'nullable|array',   
            'colors' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . time(),
            'description' => $request->description,
            'price_per_day' => $request->price_per_day,
            'status' => $request->status,
            'is_best_seller' => false, // Default false, diatur di tahap selanjutnya
            'sizes' => $request->sizes,
            'colors' => $request->colors,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price_per_day' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:available,rented,maintenance',
            'sizes' => 'nullable|array',   
            'colors' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120'
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . $product->id,
            'description' => $request->description,
            'price_per_day' => $request->price_per_day,
            'status' => $request->status,
            'sizes' => $request->sizes,
            'colors' => $request->colors,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path
                ]);
            }
        }

        return redirect()->route('admin.products.index');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $img) {
            if (Storage::disk('public')->exists($img->image_path)) {
                Storage::disk('public')->delete($img->image_path);
            }
        }
        $product->delete();

        return redirect()->route('admin.products.index');
    }

    public function destroyImage($id)
    {
        $image = ProductImage::findOrFail($id);
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        $image->delete();

        return back();
    }
}