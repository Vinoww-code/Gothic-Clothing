@extends('layouts.frontend')

@section('content')
    <!-- 1. HERO BANNER -->
    @if($banner)
        <section class="hero" style="background-image: url('{{ asset('storage/' . $banner->image_path) }}');">
            <div class="container hero-container">
                <div class="hero-content">
                    <div class="hero-ornament">
                        <i class="fa-solid fa-crown"></i>
                    </div>
                    <h1 class="hero-title">{{ $banner->title ?? 'RENT YOUR DARK ELEGANCE' }}</h1>
                    <p class="hero-subtitle">{{ $banner->subtitle ?? 'Premium Gothic Costume Rental for Cosplay, Photoshoot, Festival, Halloween, Wedding, Event, and Fashion Show.' }}</p>
                    <div class="hero-buttons">
                        <a href="#bestseller" class="btn btn-primary">{{ $banner->button_text ?? 'EXPLORE COLLECTION' }}</a>
                        <a href="{{ $banner->button_link ?? '#how-to-rent' }}" class="btn btn-outline">LEARN MORE</a>
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="hero" style="background-color: #111;">
            <div class="container hero-container">
                <div class="hero-content">
                    <div class="hero-ornament"><i class="fa-solid fa-crown"></i></div>
                    <h1 class="hero-title">RENT YOUR DARK ELEGANCE</h1>
                    <p class="hero-subtitle">Premium Gothic Costume Rental.</p>
                    <div class="hero-buttons">
                        <a href="#bestseller" class="btn btn-primary">EXPLORE COLLECTION</a>
                        <a href="#how-to-rent" class="btn btn-outline">LEARN MORE</a>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- 2. WHO WE ARE -->
    <section class="container section-padding">
        <div class="who-we-are">
            <div class="who-we-are-image">
                <img src="images/wardrobe.jpg" alt="Wardrobe">
            </div>
            <div class="who-we-are-content">
                <h2 class="section-title-left">WHO WE ARE</h2>
                <p class="who-we-are-desc">
                    Gothic Clothing menyediakan berbagai kostum dan aksesoris gothic premium dengan kualitas terbaik. Setiap produk kami rawat dengan standar kebersihan tinggi untuk memastikan kenyamanan dan kepuasan pelanggan.
                </p>
                <div class="stats-grid">
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-solid fa-chess-queen"></i></div>
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Products</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Customers</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-icon"><i class="fa-regular fa-star"></i></div>
                        <div class="stat-number">4.9 <i class="fa-solid fa-star" style="font-size:16px;"></i></div>
                        <div class="stat-label">Rating</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. BEST SELLER SECTION -->
    <section id="bestseller" class="container section-padding">
        <h2 class="section-title">Best Sellers</h2>
        <div class="product-grid">
            @forelse($bestSellers as $product)
                <div class="product-card">
                    @if($product->status != 'available')
                        <div class="badge-status" style="background: #333;">{{ ucfirst($product->status) }}</div>
                    @else
                        <div class="badge-status">Available</div>
                    @endif
                    
                    @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="product-img">
                    @else
                        <div class="product-img" style="background: #111; display:flex; align-items:center; justify-content:center; color:#555;">No Image</div>
                    @endif
                    
                    <div class="product-info">
                        <div class="product-category">{{ $product->category->name }}</div>
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-price">Rp {{ number_format($product->price_per_day, 0, ',', '.') }} / Day</div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; grid-column: span 4; color: var(--text-muted);">Tidak ada produk Best Seller yang aktif.</p>
            @endforelse
        </div>
        <div style="text-align: center; margin-top: 40px;">
            <a href="collection" class="btn btn-outline">VIEW ALL COLLECTIONS</a>
        </div>
    </section>

    <!-- 4. HOW TO RENT -->
    <section id="how-to-rent" class="section-padding" style="background: var(--bg-light); border-top: 1px solid var(--border-color); border-bottom: 1px solid var(--border-color);">
        <div class="container">
            <h2 class="section-title">How To Rent</h2>
            <div class="steps-grid">
                <div class="step-box">
                    <div class="step-number">1</div>
                    <div class="step-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <h3 class="step-title">Choose Costume</h3>
                    <p class="step-desc">Browse our collection and select the outfit that fits your dark elegance.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">2</div>
                    <div class="step-icon"><i class="fa-regular fa-calendar-check"></i></div>
                    <h3 class="step-title">Check Availability</h3>
                    <p class="step-desc">Contact us to confirm the availability of your chosen items for your dates.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">3</div>
                    <div class="step-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                    <h3 class="step-title">Payment & Deposit</h3>
                    <p class="step-desc">Complete the rental payment and security deposit to secure your booking.</p>
                </div>
                <div class="step-box">
                    <div class="step-number">4</div>
                    <div class="step-icon"><i class="fa-solid fa-truck-fast"></i></div>
                    <h3 class="step-title">Receive & Return</h3>
                    <p class="step-desc">Enjoy the costume! Return it on time to get your deposit back.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- 5. TESTIMONIALS -->
    <section id="testimonials" class="container section-padding">
        <h2 class="section-title">Client Gallery</h2>
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
            <h2 class="section-title">Frequently Asked Questions</h2>
            <div class="faq-list">
                @forelse($faqs as $faq)
                    <div class="faq-item">
                        <div class="faq-question">Q: {{ $faq->question }}</div>
                        <div class="faq-answer">A: {{ $faq->answer }}</div>
                    </div>
                @empty
                    <p style="text-align: center; color: var(--text-muted);">Belum ada FAQ.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection