<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BestSellerController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;

// ==========================================
// Frontend Routes (Bisa diakses semua orang)
// ==========================================
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collection', [CatalogController::class, 'index'])->defaults('type', 'collection')->name('collection');
Route::get('/accessories', [CatalogController::class, 'index'])->defaults('type', 'accessories')->name('accessories');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// --- RUTE CHECKOUT BOHONGAN (PASTIKAN ADA DI SINI) ---
Route::get('/checkout/{product}', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout/{product}', [CheckoutController::class, 'process'])->name('checkout.process');
Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');

// ==========================================
// Authentication Routes
// ==========================================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// Admin Routes (Hanya untuk Admin yang login)
// ==========================================
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('banners', BannerController::class)->except('show');
    
    Route::resource('products', ProductController::class)->except('show');
    Route::delete('products/images/{id}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');
    
    Route::get('bestsellers', [BestSellerController::class, 'index'])->name('bestsellers.index');
    Route::post('bestsellers/{id}/toggle', [BestSellerController::class, 'toggle'])->name('bestsellers.toggle');
    
    Route::resource('testimonials', TestimonialController::class)->except('show');
    Route::resource('faqs', FaqController::class)->except('show');
});