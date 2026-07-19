@extends('layouts.frontend')

@section('content')
    <!-- Header Sub-Page -->
    <div class="catalog-header">
        <div class="container catalog-header-inner">
            <div>
                <div class="catalog-breadcrumb">Home <span>&gt;</span> {{ $breadcrumb ?? 'Collection' }}</div>
                <h1 class="catalog-title">{{ $pageTitle ?? 'GOTHIC COSTUME' }}</h1>
            </div>
            <div class="catalog-desc">
                {{ $pageSubtitle ?? 'Temukan berbagai koleksi kostum gothic premium untuk segala kebutuhan acara Anda.' }}
                <div style="margin-top: 15px; color: var(--primary-color);">o--o----------o--o</div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container catalog-container" style="margin-top: 30px; margin-bottom: 50px;">
        
        <!-- PEMBUNGKUS ANTI-GAGAL (FLEXBOX MURNI KIRI & KANAN) -->
        <div style="display: flex; flex-wrap: wrap; gap: 30px; align-items: flex-start;">
            
            <!-- ========================================== -->
            <!-- 1. BAGIAN KIRI: FILTER SIDEBAR             -->
            <!-- ========================================== -->
            <div style="flex: 1 1 250px; max-width: 300px;">
                <div class="filter-sidebar p-4" style="background: #0a0a0a; border: 1px solid #222; border-radius: 8px; position: sticky; top: 20px;">
                    <h5 class="text-white mb-4" style="border-bottom: 1px solid #333; padding-bottom: 10px;"><i class="fa-solid fa-sliders"></i> FILTER</h5>
                    
                    <form action="{{ url()->current() }}" method="GET">
                        
                        <!-- Filter Ukuran -->
                        <div class="filter-group mb-4">
                            <h6 class="text-white mb-3" style="font-family: serif; font-size: 14px; letter-spacing: 1px;">UKURAN</h6>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @php $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']; @endphp
                                @foreach($sizes as $s)
                                    <label style="cursor: pointer; margin: 0;">
                                        <input type="checkbox" name="sizes[]" value="{{ $s }}" 
                                            {{ in_array($s, request('sizes', [])) ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                            style="display: none;">
                                        
                                        <!-- Box ukuran -->
                                        <div style="border: 1px solid {{ in_array($s, request('sizes', [])) ? '#8b0000' : '#444' }}; 
                                                    background-color: {{ in_array($s, request('sizes', [])) ? 'rgba(139,0,0,0.2)' : 'transparent' }};
                                                    padding: 6px 12px; color: {{ in_array($s, request('sizes', [])) ? '#fff' : '#aaa' }}; 
                                                    border-radius: 4px; font-size: 12px; font-weight: bold; transition: 0.2s;">
                                            {{ $s }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Filter Warna -->
                        <div class="filter-group mb-4">
                            <h6 class="text-white mb-3" style="font-family: serif; font-size: 14px; letter-spacing: 1px;">WARNA</h6>
                            <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @php 
                                    $colors = [
                                        'Red' => '#8b0000', 
                                        'Maroon' => '#5e0000', 
                                        'Purple' => '#4b0082', 
                                        'White' => '#ffffff', 
                                        'Grey' => '#808080', 
                                        'Black' => '#111111'
                                    ]; 
                                @endphp
                                @foreach($colors as $name => $hex)
                                    <label style="cursor: pointer; margin: 0;" title="{{ $name }}">
                                        <input type="checkbox" name="colors[]" value="{{ $name }}" 
                                            {{ in_array($name, request('colors', [])) ? 'checked' : '' }}
                                            onchange="this.form.submit()"
                                            style="display: none;">
                                        
                                        <!-- Lingkaran warna -->
                                        <div style="width: 28px; height: 28px; border-radius: 50%; background: {{ $hex }}; 
                                                    border: 2px solid {{ in_array($name, request('colors', [])) ? '#ff4444' : '#333' }};
                                                    box-shadow: {{ in_array($name, request('colors', [])) ? '0 0 8px rgba(255,0,0,0.5)' : 'none' }};
                                                    transition: 0.2s;">
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Filter Harga -->
                        <div class="filter-group mb-4">
                            <h6 class="text-white mb-3" style="font-family: serif; font-size: 14px; letter-spacing: 1px;">HARGA MAKSIMAL</h6>
                            <input type="range" class="form-range w-100" name="max_price" 
                                   min="0" max="500000" step="25000" 
                                   value="{{ request('max_price', 500000) }}"
                                   oninput="document.getElementById('priceVal').innerText = 'Rp' + parseInt(this.value).toLocaleString('id-ID')"
                                   onchange="this.form.submit()"
                                   style="accent-color: #8b0000; cursor: pointer;">
                            <div class="d-flex justify-content-between mt-2" style="font-size: 13px; color: #aaa;">
                                <span>Rp0</span>
                                <span id="priceVal" style="color: #fff; font-weight: bold;">Rp{{ number_format(request('max_price', 500000), 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Tombol Aksi (Terapkan & Reset) -->
                        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px;">
                            <button type="submit" class="btn w-100" style="background: #8b0000; color: white; border: none; font-weight: bold; padding: 10px;">
                                TERAPKAN FILTER
                            </button>
                            
                            @if(request()->has('sizes') || request()->has('colors') || request('max_price') != 500000)
                                <a href="{{ url()->current() }}" class="btn w-100" style="background: transparent; color: #fff; border: 1px solid #444; padding: 10px; text-align: center; text-decoration: none;">
                                    RESET FILTER
                                </a>
                            @endif
                        </div>

                    </form>
                </div>
            </div>

            <!-- ========================================== -->
            <!-- 2. BAGIAN KANAN: PRODUCT GRID              -->
            <!-- ========================================== -->
            <div style="flex: 3 1 600px;">
                <main class="catalog-main">
                    <div class="catalog-topbar">
                        <div>Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</div>
                        <div style="display: flex; gap: 15px; align-items: center;">
                            <div class="catalog-sort">
                                <select style="background: #111; color: white; border: 1px solid #333; padding: 5px 10px; border-radius: 4px;">
                                    <option>Urutkan: Terbaru</option>
                                    <option>Harga: Rendah ke Tinggi</option>
                                    <option>Harga: Tinggi ke Rendah</option>
                                </select>
                            </div>
                            <div style="display: flex; gap: 5px; color: #fff;">
                                <i class="fa-solid fa-border-all" style="color: var(--primary-color); font-size: 18px; cursor: pointer;"></i>
                                <i class="fa-solid fa-list" style="font-size: 18px; cursor: pointer; color: #555;"></i>
                            </div>
                        </div>
                    </div>

                    <div class="product-grid">
                        @forelse($products as $product)
                            <div class="product-card-v2">
                                <!-- Tombol Wishlist -->
                                <button class="btn-wishlist"><i class="fa-regular fa-heart"></i></button>
                                
                                <!-- Gambar Produk -->
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="product-img-v2">
                                @else
                                    <div class="product-img-v2" style="background: #1a1a1a; display:flex; align-items:center; justify-content:center;">No Image</div>
                                @endif

                                <!-- Badge Status -->
                                @if($product->status == 'available')
                                    <div class="badge-tersedia">TERSEDIA</div>
                                @else
                                    <div class="badge-tersedia" style="background: #8b0000;">DISEWA</div>
                                @endif

                                <!-- Detail Produk -->
                                <div class="product-info-v2">
                                    <h3 class="product-title-v2">{{ $product->name }}</h3>
                                    <div class="product-cat-v2">{{ $product->category->name }}</div>
                                    
                                    <div class="product-price-row">
                                        <div class="product-price-v2">Rp{{ number_format($product->price_per_day, 0, ',', '.') }} <span style="font-size:11px; font-weight:normal; color:var(--text-muted);">/ hari</span></div>
                                        <div class="product-rating-v2">
                                            <i class="fa-solid fa-star"></i> 4.9 <span>(42)</span>
                                        </div>
                                    </div>

                                    <!-- DI SINI TOMBOL SUDAH MENGGUNAKAN route('checkout') RESMI LARAVEL -->
                                    <a href="{{ route('checkout', $product->id) }}" class="btn-sewa" style="text-decoration: none; display: block; text-align: center;">
                                        <i class="fa-solid fa-cart-shopping"></i> SEWA SEKARANG
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p style="grid-column: span 4; text-align:center; color: var(--text-muted); padding: 50px 0;">Belum ada produk di kategori ini.</p>
                        @endforelse
                    </div>

                    <!-- Custom Pagination -->
                    @if($products->hasPages())
                        <div class="pagination-wrap">
                            @if(!$products->onFirstPage())
                                <a href="{{ $products->previousPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-left"></i></a>
                            @endif
                            
                            <div class="page-btn active">1</div>
                            <div class="page-btn">2</div>

                            @if($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="page-btn"><i class="fa-solid fa-chevron-right"></i></a>
                            @endif
                        </div>
                    @endif
                </main>
            </div>
            
        </div>
    </div>
@endsection