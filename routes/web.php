<?php

use Illuminate\Support\Facades\Route;

// Controller Frontend & Auth User
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\DocumentController;

// Controller Admin
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\BestSellerController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\FinanceController;


/*
|--------------------------------------------------------------------------
| 1. FRONTEND / PUBLIC ROUTES (Dapat diakses semua orang)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/collection', [CatalogController::class, 'index'])->defaults('type', 'collection')->name('collection');
Route::get('/accessories', [CatalogController::class, 'index'])->defaults('type', 'accessories')->name('accessories');
Route::get('/product/{slug}', [CatalogController::class, 'show'])->name('product.show');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Protected Customer Orders, Checkout & Document Routes (Requires Authentication)
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [UserOrderController::class, 'index'])->name('my.orders');
    Route::post('/my-orders/{order}/cancel', [UserOrderController::class, 'cancel'])->name('my.orders.cancel');
    Route::get('/checkout/{product}', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/{product}', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order_code}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/status/{order_code}', [CheckoutController::class, 'checkStatus'])->name('checkout.status');
    Route::get('/documents/order/{order}/{type}', [DocumentController::class, 'serveOrderDocument'])->name('documents.order');
});


/*
|--------------------------------------------------------------------------
| 2. USER AUTHENTICATION ROUTES (Login / Register User)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');


/*
|--------------------------------------------------------------------------
| 3. ADMIN AUTHENTICATION ROUTES (Login / Logout Khusus Admin)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});


/*
|--------------------------------------------------------------------------
| 4. ADMIN PROTECTED PANEL (Dikelola oleh IsAdmin Middleware)
|--------------------------------------------------------------------------
*/

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard & Laporan Keuangan
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

    // Manajemen Pesanan / Order Management
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

    // Manajemen Kategori & Banner
    Route::resource('categories', CategoryController::class)->except('show');
    Route::resource('banners', BannerController::class)->except('show');

    // Manajemen Produk & Foto Produk
    Route::resource('products', ProductController::class)->except('show');
    Route::delete('products/images/{id}', [ProductController::class, 'destroyImage'])->name('products.images.destroy');

    // Manajemen Best Sellers
    Route::get('bestsellers', [BestSellerController::class, 'index'])->name('bestsellers.index');
    Route::post('bestsellers/{id}/toggle', [BestSellerController::class, 'toggle'])->name('bestsellers.toggle');

    // Manajemen Testimoni & FAQ
    Route::resource('testimonials', TestimonialController::class)->except('show');
    Route::resource('faqs', FaqController::class)->except('show');

});