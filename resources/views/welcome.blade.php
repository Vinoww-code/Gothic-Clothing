@extends('layouts.frontend')

@section('content')

<!-- ========================================== -->
<!-- CSS RESPONSIF KHUSUS HALAMAN HOME          -->
<!-- ========================================== -->
<style>
    /* Pastikan gambar hero menutupi seluruh area dengan proporsional */
    .hero {
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    /* ==== PENGATURAN KHUSUS LAYAR HP & TABLET (Max 768px) ==== */
    @media (max-width: 768px) {
        /* 1. HERO BANNER */
        .hero-content {
            width: 100% !important; /* Memenuhi layar, tidak cuma di kiri */
            text-align: center; /* Teks ke tengah */
            padding: 20px;
        }
        .hero-title {
            font-size: 32px !important; /* Judul diperkecil agar tidak menumpuk */
            line-height: 1.2;
        }
        .hero-buttons {
            display: flex;
            flex-direction: column; /* Tombol menjadi atas-bawah */
            gap: 15px;
            width: 100%;
        }
        .hero-buttons .btn {
            width: 100%; /* Tombol memanjang penuh */
        }

        /* 2. WHO WE ARE */
        .who-we-are {
            display: flex;
            flex-direction: column; /* Gambar di atas, teks di bawah */
            gap: 30px;
        }
        .who-we-are-image {
            width: 100% !important;
            max-width: 100% !important;
        }
        .who-we-are-image img {
            width: 100%;
            height: auto;
            border-radius: 8px; /* Tambahan estetika */
        }
        .section-title-left {
            text-align: center; /* Judul ke tengah */
        }
        .who-we-are-desc {
            text-align: center;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr) !important; /* Kotak statistik jadi 2 kolom */
            gap: 15px;
        }

        /* 3. BEST SELLER (Produk) */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr) !important; /* Menjadi 2 kolom (Kiri-Kanan) */
            gap: 10px;
        }
        .product-name {
            font-size: 14px !important;
        }
        .product-price {
            font-size: 13px !important;
        }

        /* 4. HOW TO RENT */
        .steps-grid {
            display: grid;
            grid-template-columns: 1fr !important; /* Menurun ke bawah 1 kolom */
            gap: 20px;
        }

        /* 5. TESTIMONIALS */
        .testi-grid {
            display: grid;
            grid-template-columns: 1fr !important; /* Menurun ke bawah 1 kolom */
            gap: 20px;
        }
    }

    /* ==== PENGATURAN KHUSUS LAYAR HP SANGAT KECIL (Max 480px) ==== */
    @media (max-width: 480px) {
        .product-grid {
            grid-template-columns: 1fr !important; /* Produk menjadi 1 kolom besar memanjang */
        }
        .stats-grid {
            grid-template-columns: 1fr !important; /* Statistik menjadi 1 kolom memanjang */
        }
        .stat-box {
            align-items: center;
        }
    }
</style>
<!-- ========================================== -->


    <!-- 1. HERO BANNER -->
    @if($banner)
        <section class="hero" style="background-image: url('{{ asset('storage/' . $banner->image_path) }}');">
            <div class="container hero-container">
                <div class="hero-content">
                    <div class="hero-ornament">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <h1 class="hero-title">{{ $banner->title ?? 'SEWA ELEGANSI GELAPMU' }}</h1>
                    <p class="hero-subtitle">{{ $banner->subtitle ?? 'Penyewaan Kostum Gothic Premium untuk Cosplay, Sesi Foto, Festival, Halloween, Pernikahan, Acara, dan Fashion Show.' }}</p>
                    <div class="hero-buttons">
                        <a href="#bestseller" class="btn btn-primary">{{ $banner->button_text ?? 'JELAJAHI KOLEKSI' }}</a>
                        <a href="{{ $banner->button_link ?? '#how-to-rent' }}" class="btn btn-outline">PELAJARI LEBIH LANJUT</a>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="hero" style="background-color: #111;">
            <div class="container hero-container">
                <div class="hero-content">
                    <div class="hero-ornament"><i class="fa-solid fa-crown"></i></div>
                    <h1 class="hero-title">SEWA ELEGANSI GELAPMU</h1>
                    <p class="hero-subtitle">Penyewaan Kostum Gothic Premium.</p>
                    <div class="hero-buttons">
                        <a href="#bestseller" class="btn btn-primary">JELAJAHI KOLEKSI</a>
                        <a href="#how-to-rent" class="btn btn-outline">PELAJARI LEBIH LANJUT</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- 2. WHO WE ARE -->
    <section class="container section-padding">
        <div class="who-we-are">
            <div class="who-we-are-image">
                <img src="images/wardrobe.jpg" alt="Lemari Kostum">
            </div>
            <div class="who-we-are-content">
                <h2 class="section-title-left">TENTANG KAMI</h2>
                <p class="who-we-are-desc">
                    Gothic Clothing menyediakan berbagai kostum dan aksesoris gothic premium dengan kualitas terbaik. Setiap produk kami rawat dengan standar kebersihan tinggi untuk memastikan kenyamanan dan kepuasan pelanggan.
                </p>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-solid fa-chess-queen"></i></div>
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Produk</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Pelanggan</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-regular fa-star"></i></div>
                        <div class="stat-number">4.9 <i class="fa-solid fa-star" style="font-size:16px;"></i></div>
                        <div class="stat-label">Penilaian</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. BEST SELLER SECTION -->
    <section id="bestseller" class="container section-padding">
        <h2 class="section-title">Produk Terlaris</h2>
        <div class="product-grid">
            @forelse($bestSellers as $product)
                <div class="product-card">
                    @if($product->status != 'available')
                        <div class="badge-status" style="background: #333;">{{ $product->status == 'rented' ? 'Disewa' : ucfirst($product->status) }}</div>
                    @else
                        <div class="badge-status">Tersedia</div>
                    @endif
                    
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="product-img">
                    @else
                        <div class="product-img" style="background: #111; display:flex; align-items:center; justify-content:center; color:#555;">Tidak Ada Gambar</div>
                    @endif
                    
                    <div class="product-info">
                        <div class="product-category">{{ $product->category->name }}</div>
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-price">Rp {{ number_format($product->price_per_day, 0, ',', '.') }} / Hari</div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; grid-column: span 4; color: var(--text-muted);">Tidak ada produk Terlaris yang aktif.</p>
            @endforelse
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="collection" class="btn btn-outline">LIHAT SEMUA KOLEKSI</a>
        </div>
    </section>

    <!-- 4. HOW TO RENT -->
    <section id="how-to-rent" class="section-padding" style="background: var(--bg-light); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="container">
            <h2 class="section-title">Cara Menyewa</h2>
            <div class="steps-grid">
                <div class="step-box">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <h3 class="step-title">Pilih Kostum</h3>
                    <p class="step-desc">Jelajahi koleksi kami dan pilih kostum yang sesuai dengan gaya dan kebutuhan acara Anda.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="fa-regular fa-calendar-check"></i></div>
                    <h3 class="step-title">Cek Ketersediaan</h3>
                    <p class="step-desc">Hubungi kami untuk memastikan ketersediaan produk yang Anda pilih pada tanggal yang diinginkan.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="fa-solid fa-id-card"></i></div>
                    <h3 class="step-title">Jaminan & Pembayaran</h3>
                    <p class="step-desc">Lakukan pembayaran sewa dan serahkan jaminan identitas (seperti KTP/SIM) atau deposit untuk mengamankan pesanan Anda.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">4</div>
                    <div class="step-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <h3 class="step-title">Terima & Kembalikan</h3>
                    <p class="step-desc">Nikmati kostumnya! Kembalikan tepat waktu untuk mendapatkan kembali jaminan atau deposit Anda.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- 5. TESTIMONIALS -->
    <section id="testimonials" class="container section-padding">
        <h2 class="section-title">Galeri Klien</h2>
        <div class="testi-grid">
            @forelse($testimonials as $testimonial)
                <div class="testi-card">
                    <!-- Foto Model/Klien -->
                    @if($testimonial->avatar_path)
                        <img src="{{ asset('storage/' . $testimonial->avatar_path) }}" alt="{{ $testimonial->name }}" class="testi-image">
                    @else
                        <div class="testi-image" style="background: #111; display:flex; align-items:center; justify-content:center; color:#444; font-size:40px;"><i class="fa-solid fa-camera"></i></div>
                    @endif
                    
                    <!-- Detail Testimoni -->
                    <div class="testi-content">
                        <div class="testi-rating">
                            @for($i=1; $i<=5; $i++)
                                @if($i <= $testimonial->rating)
                                    <i class="fa-solid fa-star"></i>
                                @else
                                    <i class="fa-regular fa-star"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="testi-comment">"{{ $testimonial->comment }}"</p>
                        <h4 class="testi-name">{{ $testimonial->name }}</h4>
                        <div class="testi-loc">{{ $testimonial->location }}</div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; grid-column: span 3; color: var(--text-muted);">Belum ada galeri klien.</p>
            @endforelse
        </div>
    </section>

    <!-- 6. FAQ -->
    <section id="faq" class="section-padding" style="background: #0d0d0d; border-top: 1px solid var(--border-color);">
        <div class="container">
            <h2 class="section-title">Pertanyaan Umum (FAQ)</h2>
            <div class="faq-list">
                @forelse($faqs as $faq)
                    <div class="faq-item">
                        <div class="faq-question">T: {{ $faq->question }}</div>
                        <div class="faq-answer">J: {{ $faq->answer }}</div>
                    </div>
                @empty
                    <p style="text-align: center; color: var(--text-muted);">Belum ada FAQ.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection