@extends('layouts.frontend')

@section('content')

<!-- KODE CSS RESPONSIF KHUSUS UNTUK HALAMAN INI -->
<style>
    /* ========================================== */
    /* 1. STYLING DASAR (DESKTOP)                 */
    /* ========================================== */
    .katalog-wrapper {
        display: flex;
        flex-wrap: nowrap;
        gap: 30px;
        align-items: flex-start;
    }
    
    .filter-wrapper {
        width: 280px; 
        flex-shrink: 0;
    }
    
    .product-wrapper {
        flex-grow: 1;
        width: 100%;
        min-width: 0;
    }

    /* MEMBUAT FOTO PRODUK JADI PERSEGI (1:1) */
    .product-img-v2 {
        width: 100%;
        aspect-ratio: 1 / 1 !important; /* Memaksa bentuk kotak persegi */
        object-fit: cover !important; /* Mencegah gambar melar/gepeng */
        height: auto !important; /* Menghapus batasan tinggi bawaan */
        display: block;
    }

    /* Mengatur Grid di Laptop agar rapi (4 kolom atau menyesuaikan) */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* 4 kotak ke samping di PC */
        gap: 20px;
    }
    .product-card-v2 {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    }

    .product-info-v2 {
        display: flex;
        flex-direction: column;
        gap: 8px; /* Memberikan jarak nafas antar teks */
        padding-top: 15px;
    }

    .badge-tersedia {
        position: relative !important; /* Membatalkan efek melayang yang menabrak teks */
        display: inline-block;
        width: max-content;
        margin-top: 10px;
        margin-bottom: 5px;
        border-radius: 4px;
    }

    .product-cat-v2 {
        color: #888;
        font-size: 12px;
        margin-bottom: 5px;
    }

    /* ========================================== */
    /* 2. PENGATURAN KHUSUS TABLET & HP           */
    /* ========================================== */
    @media (max-width: 992px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr); /* 3 kotak di layar sedang */
        }
    }

    @media (max-width: 768px) {
        .katalog-wrapper {
            flex-direction: column;
            gap: 20px;
        }
        
        .filter-wrapper {
            width: 100%;
            max-width: 100%;
            position: relative !important;
            top: 0 !important;
        }
        
        /* Grid Produk jadi 2 Kolom di HP */
        .product-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 12px;
        }

        .catalog-title { font-size: 24px !important; }
        .catalog-desc { font-size: 13px !important; }

        .product-title-v2 {
            font-size: 13px !important;
            line-height: 1.4;
            margin-bottom: 5px;
            white-space: normal;
        }
        .product-price-v2 { font-size: 14px !important; }
        .btn-sewa { font-size: 12px !important; padding: 8px !important; }
        .badge-tersedia { font-size: 10px !important; padding: 4px 8px !important; }
    }
    

    /* ========================================== */
    /* 3. PENGATURAN KHUSUS HP LAYAR KECIL        */
    /* ========================================== */
    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr !important; /* 1 kolom memanjang */
            gap: 15px;
        }
        .product-title-v2 { font-size: 16px !important; }
        .product-price-v2 { font-size: 16px !important; }
        .btn-sewa { font-size: 14px !important; padding: 10px !important; }
    }
</style>
<!-- AKHIR CSS RESPONSIF -->

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
    
    <div class="katalog-wrapper">
        
        <!-- ========================================== -->
        <!-- 1. BAGIAN KIRI: FILTER & PENCARIAN         -->
        <!-- ========================================== -->
        <div class="filter-wrapper">
            <div class="filter-sidebar p-4" style="background: #0a0a0a; border: 1px solid #222; border-radius: 8px; position: sticky; top: 20px;">
                <h5 class="text-white" style="border-bottom: 1px solid #333; padding-bottom: 15px; margin-bottom: 30px; font-size: 16px; font-weight: bold; letter-spacing: 1px;">
                    <i class="fa-solid fa-sliders" style="margin-right: 8px;"></i> FILTER
                </h5>
                
                <form action="{{ url()->current() }}" method="GET">
                    
                    <!-- Kolom Pencarian -->
                    <div class="filter-group" style="margin-bottom: 35px;">
                        <h6 style="color: #bbb; font-family: sans-serif; font-size: 12px; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px; text-transform: uppercase;">Pencarian</h6>
                        <div style="display: flex;">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama produk..." 
                                   style="width: 100%; padding: 10px 12px; background: #111; border: 1px solid #333; color: white; border-radius: 6px; outline: none; font-size: 13px; transition: 0.3s;"
                                   onfocus="this.style.borderColor='#8b0000'" onblur="this.style.borderColor='#333'">
                        </div>
                    </div>

                    <!-- Filter Ukuran -->
                    <div class="filter-group" style="margin-bottom: 35px;">
                        <h6 style="color: #bbb; font-family: sans-serif; font-size: 12px; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px; text-transform: uppercase;">Ukuran</h6>
                        <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @php $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL']; @endphp
                            @foreach($sizes as $s)
                                <label style="cursor: pointer; margin: 0;">
                                    <input type="checkbox" name="sizes[]" value="{{ $s }}" 
                                        {{ in_array($s, request('sizes', [])) ? 'checked' : '' }}
                                        onchange="this.form.submit()"
                                        style="display: none;">
                                    
                                    <div style="border: 1px solid {{ in_array($s, request('sizes', [])) ? '#8b0000' : '#333' }}; 
                                                background-color: {{ in_array($s, request('sizes', [])) ? '#8b0000' : '#111' }};
                                                padding: 8px 14px; color: {{ in_array($s, request('sizes', [])) ? '#fff' : '#aaa' }}; 
                                                border-radius: 4px; font-size: 12px; font-weight: bold; transition: 0.2s; text-align: center; min-width: 40px;">
                                        {{ $s }}
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Filter Warna -->
                    <div class="filter-group" style="margin-bottom: 35px;">
                        <h6 style="color: #bbb; font-family: sans-serif; font-size: 12px; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px; text-transform: uppercase;">Warna</h6>
                        <div style="display: flex; flex-wrap: wrap; gap: 10px;">
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
                                    
                                    <div style="width: 30px; height: 30px; border-radius: 50%; background: {{ $hex }}; 
                                                border: 2px solid {{ in_array($name, request('colors', [])) ? '#ff4444' : '#444' }};
                                                box-shadow: {{ in_array($name, request('colors', [])) ? '0 0 8px rgba(255,0,0,0.5)' : 'none' }};
                                                transition: 0.2s;
                                                position: relative;">
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Filter Harga -->
                    <div class="filter-group" style="margin-bottom: 30px;">
                        <h6 style="color: #bbb; font-family: sans-serif; font-size: 12px; font-weight: 700; letter-spacing: 1px; margin-bottom: 8px; text-transform: uppercase;">Harga Maksimal</h6>
                        <input type="range" class="form-range w-100" name="max_price" 
                               min="0" max="500000" step="25000" 
                               value="{{ request('max_price', 500000) }}"
                               oninput="document.getElementById('priceVal').innerText = 'Rp' + parseInt(this.value).toLocaleString('id-ID')"
                               onchange="this.form.submit()"
                               style="accent-color: #8b0000; cursor: pointer;">
                        <div class="d-flex justify-content-between mt-2" style="font-size: 12px; color: #888;">
                            <span>Rp0</span>
                            <span id="priceVal" style="color: #fff; font-weight: bold;">Rp{{ number_format(request('max_price', 500000), 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 25px;">
                        <button type="submit" class="btn w-100" style="background: #8b0000; color: white; border: none; font-weight: bold; padding: 12px; border-radius: 6px; font-size: 13px; letter-spacing: 1px;">
                            TERAPKAN FILTER
                        </button>
                        
                        @if(request()->has('sizes') || request()->has('colors') || request('max_price') != 500000 || request()->has('search'))
                            <a href="{{ url()->current() }}" class="btn w-100" style="background: transparent; color: #aaa; border: 1px solid #444; padding: 12px; text-align: center; text-decoration: none; border-radius: 6px; font-size: 13px; font-weight: bold; letter-spacing: 1px; transition: 0.3s;" onmouseover="this.style.color='#fff'; this.style.borderColor='#fff';" onmouseout="this.style.color='#aaa'; this.style.borderColor='#444';">
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
        <div class="product-wrapper">
            <main class="catalog-main">
                <div class="catalog-topbar">
                    <div style="font-size: 14px;">Menampilkan {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} dari {{ $products->total() }} produk</div>
                </div>

                <div class="product-grid">
                    @forelse($products as $product)
                        <div class="product-card-v2">
                            <button class="btn-wishlist"><i class="fa-regular fa-heart"></i></button>
                            
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="product-img-v2">
                            @else
                                <div class="product-img-v2" style="background: #1a1a1a; display:flex; align-items:center; justify-content:center; color: #555;">No Image</div>
                            @endif

                            @if($product->status == 'available')
                                <div class="badge-tersedia">TERSEDIA</div>
                            @else
                                <div class="badge-tersedia" style="background: #8b0000;">DISEWA</div>
                            @endif

                            <div class="product-info-v2">
                                <h3 class="product-title-v2">{{ $product->name }}</h3>
                                <div class="product-cat-v2">{{ $product->category->name }}</div>
                                
                                <div class="product-price-row" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                    <!-- WARNA HARGA DIUBAH KE PUTIH (#ffffff) -->
                                    <div class="product-price-v2" style="color: #ffffff; font-weight: bold;">Rp{{ number_format($product->price_per_day, 0, ',', '.') }}</div>
                                </div>

                                <a href="{{ route('checkout', $product->id) }}" class="btn-sewa" style="text-decoration: none; display: block; text-align: center; background: #8b0000; color: white; padding: 10px; border-radius: 4px;">
                                    <i class="fa-solid fa-cart-shopping"></i> SEWA
                                </a>
                            </div>
                        </div>
                    @empty
                        <p style="grid-column: 1 / -1; text-align:center; color: #aaa; padding: 50px 0;">Produk tidak ditemukan.</p>
                    @endforelse
                </div>

                <!-- Custom Pagination -->
                @if($products->hasPages())
                    <div class="pagination-wrap" style="margin-top: 30px; display: flex; justify-content: center; gap: 5px;">
                        @if(!$products->onFirstPage())
                            <a href="{{ $products->previousPageUrl() }}" class="page-btn" style="padding: 5px 12px; background: #222; color: white; text-decoration: none;"><i class="fa-solid fa-chevron-left"></i></a>
                        @endif
                        
                        <div class="page-btn active" style="padding: 5px 12px; background: #8b0000; color: white;">1</div>

                        @if($products->hasMorePages())
                            <a href="{{ $products->nextPageUrl() }}" class="page-btn" style="padding: 5px 12px; background: #222; color: white; text-decoration: none;"><i class="fa-solid fa-chevron-right"></i></a>
                        @endif
                    </div>
                @endif
            </main>
        </div>
        
    </div>
</div>
@endsection