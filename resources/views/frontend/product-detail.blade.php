@extends('layouts.frontend')

@section('content')
<style>
    .product-detail-section { padding-top: 130px; padding-bottom: 80px; }
    .product-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; align-items: start; }
    @media (max-width: 900px) { .product-detail-grid { grid-template-columns: 1fr; gap: 30px; } }

    /* Gallery */
    .gallery-main-wrapper { position: relative; border-radius: 8px; overflow: hidden; border: 1px solid #222; background: #111; }
    .gallery-main-img { width: 100%; aspect-ratio: 1/1; object-fit: cover; display: block; transition: transform 0.3s ease; }
    .gallery-thumbs { display: flex; gap: 12px; margin-top: 15px; overflow-x: auto; padding-bottom: 5px; }
    .gallery-thumb { width: 75px; height: 75px; object-fit: cover; border-radius: 6px; border: 2px solid #222; cursor: pointer; transition: 0.2s; flex-shrink: 0; background: #111; }
    .gallery-thumb.active, .gallery-thumb:hover { border-color: #8b0000; }

    /* Product Meta */
    .product-badge-cat { display: inline-block; font-size: 12px; font-weight: 700; text-transform: uppercase; color: #8b0000; letter-spacing: 1.5px; margin-bottom: 8px; }
    .product-title { font-size: 34px; line-height: 1.2; margin: 0 0 15px; color: #fff; }
    .product-price-box { display: flex; align-items: baseline; gap: 10px; margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #222; }
    .product-price-val { font-size: 28px; font-weight: bold; color: #4ade80; }
    .product-price-unit { font-size: 14px; color: var(--text-muted); }

    /* Attributes pills */
    .pill-list { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; }
    .pill-item { background: #181818; border: 1px solid #333; padding: 6px 14px; border-radius: 4px; font-size: 13px; color: #ddd; }

    /* Rental Calculator Widget */
    .rental-calculator { background: #141414; border: 1px solid #282828; border-radius: 8px; padding: 22px; margin: 25px 0; }
    .rental-calculator h3 { font-size: 16px; margin: 0 0 15px; color: #fff; display: flex; align-items: center; gap: 8px; }
    .rental-calc-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
    @media (max-width: 500px) { .rental-calc-grid { grid-template-columns: 1fr; } }
    
    .calc-input-group label { display: block; font-size: 12px; color: var(--text-muted); margin-bottom: 5px; font-weight: 600; }
    .calc-input { width: 100%; padding: 10px 12px; background: #0a0a0a; border: 1px solid #333; border-radius: 4px; color: #fff; font-size: 13px; box-sizing: border-box; }
    .calc-input:focus { outline: none; border-color: #8b0000; }

    .calc-summary { display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; background: #0a0a0a; border-radius: 6px; border: 1px solid #222; margin-bottom: 18px; font-size: 14px; }

    /* Tabs / Info Sections */
    .info-accordion { margin-top: 30px; border-top: 1px solid #222; }
    .accordion-item { border-bottom: 1px solid #222; padding: 18px 0; }
    .accordion-title { font-size: 15px; font-weight: 600; color: #fff; cursor: pointer; display: flex; justify-content: space-between; align-items: center; }
    .accordion-content { margin-top: 12px; font-size: 13px; color: var(--text-muted); line-height: 1.7; }
</style>

<div class="product-detail-section">
    <div class="container">
        <!-- Breadcrumb -->
        <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 25px;">
            <a href="{{ route('home') }}" style="color: var(--text-muted);">Beranda</a> &nbsp;/&nbsp; 
            <a href="{{ $product->category->type == 'accessory' ? route('accessories') : route('collection') }}" style="color: var(--text-muted);">
                {{ $product->category->type == 'accessory' ? 'Accessories' : 'Collection' }}
            </a> &nbsp;/&nbsp;
            <span style="color: #fff;">{{ $product->name }}</span>
        </div>

        <div class="product-detail-grid">
            <!-- LEFT: Image Gallery -->
            <div>
                <div class="gallery-main-wrapper">
                    @if($product->status != 'available')
                        <div class="badge-status" style="position: absolute; top: 15px; left: 15px; z-index: 5; background: #333;">
                            {{ $product->status == 'rented' ? 'Sedang Disewa' : ucfirst($product->status) }}
                        </div>
                    @else
                        <div class="badge-status" style="position: absolute; top: 15px; left: 15px; z-index: 5;">
                            Tersedia untuk Disewa
                        </div>
                    @endif

                    @if($product->images->count() > 0)
                        <img id="mainImage" src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="gallery-main-img">
                    @else
                        <div class="gallery-main-img" style="display: flex; align-items: center; justify-content: center; color: #555;">
                            Tidak Ada Gambar
                        </div>
                    @endif
                </div>

                @if($product->images->count() > 1)
                    <div class="gallery-thumbs">
                        @foreach($product->images as $index => $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}" 
                                 class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" 
                                 alt="Thumbnail {{ $index + 1 }}"
                                 onclick="selectImage('{{ asset('storage/' . $img->image_path) }}', this)">
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- RIGHT: Product Info & Booking Widget -->
            <div>
                <div class="product-badge-cat">
                    <i class="fa-solid fa-crown" style="margin-right: 4px;"></i> {{ $product->category->name }}
                </div>
                <h1 class="product-title">{{ $product->name }}</h1>

                <div class="product-price-box">
                    <div class="product-price-val">Rp {{ number_format($product->price_per_day, 0, ',', '.') }}</div>
                    <div class="product-price-unit">/ Hari Sewa</div>
                </div>

                <!-- Sizes & Colors (if available) -->
                @if(!empty($product->sizes) && is_array($product->sizes))
                    <div style="margin-bottom: 18px;">
                        <strong style="font-size: 13px; color: #fff; text-transform: uppercase; letter-spacing: 1px;">Pilihan Ukuran Tersedia:</strong>
                        <div class="pill-list">
                            @foreach($product->sizes as $size)
                                <span class="pill-item"><i class="fa-solid fa-ruler-combined" style="margin-right: 4px; color: #8b0000;"></i> {{ $size }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($product->colors) && is_array($product->colors))
                    <div style="margin-bottom: 20px;">
                        <strong style="font-size: 13px; color: #fff; text-transform: uppercase; letter-spacing: 1px;">Pilihan Warna:</strong>
                        <div class="pill-list">
                            @foreach($product->colors as $color)
                                <span class="pill-item"><i class="fa-solid fa-palette" style="margin-right: 4px; color: #8b0000;"></i> {{ $color }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Description -->
                <div style="color: #cbd5e1; line-height: 1.8; font-size: 14px; margin-bottom: 25px;">
                    {{ $product->description ?? 'Kostum gothic premium dengan detail pengerjaan halus, kain velvet/brokat pilihan, dan aksen logam berornamen antik.' }}
                </div>

                <!-- Interactive Rental Booking Widget -->
                @if($product->status === 'available')
                    <div class="rental-calculator">
                        <h3><i class="fa-solid fa-calendar-check" style="color: #8b0000;"></i> Simulasi & Jadwal Sewa</h3>
                        <div class="rental-calc-grid">
                            <div class="calc-input-group">
                                <label for="calcStartDate">Tanggal Mulai Sewa</label>
                                <input type="date" id="calcStartDate" class="calc-input" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" onchange="updateRentalCalc()">
                            </div>
                            <div class="calc-input-group">
                                <label for="calcEndDate">Tanggal Selesai Sewa</label>
                                <input type="date" id="calcEndDate" class="calc-input" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+1 day')) }}" onchange="updateRentalCalc()">
                            </div>
                        </div>

                        <div class="calc-summary">
                            <div>
                                <span style="color: var(--text-muted); font-size: 12px; display: block;">Durasi:</span>
                                <strong id="calcDurationText" style="color: #fff;">2 Hari Sewa</strong>
                            </div>
                            <div style="text-align: right;">
                                <span style="color: var(--text-muted); font-size: 12px; display: block;">Estimasi Total Biaya:</span>
                                <strong id="calcTotalText" style="color: #4ade80; font-size: 18px;">Rp {{ number_format($product->price_per_day * 2, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <a id="btnProceedCheckout" href="{{ route('checkout', $product->id) }}?start_date={{ date('Y-m-d') }}&end_date={{ date('Y-m-d', strtotime('+1 day')) }}" class="btn btn-primary" style="display: block; text-align: center; width: 100%; box-sizing: border-box;">
                            <i class="fa-solid fa-cart-shopping" style="margin-right: 6px;"></i> SEWA SEKARANG
                        </a>
                    </div>
                @else
                    <div style="background: #181818; border: 1px solid #333; padding: 20px; border-radius: 8px; text-align: center; margin: 25px 0;">
                        <i class="fa-solid fa-clock" style="font-size: 28px; color: #fbbf24; margin-bottom: 8px; display: block;"></i>
                        <strong style="color: #fff;">Kostum Sedang Disewa atau Belum Tersedia</strong>
                        <p style="color: var(--text-muted); font-size: 13px; margin: 5px 0 0;">
                            Silakan periksa koleksi gothic lainnya atau hubungi kami untuk informasi tanggal kembali.
                        </p>
                    </div>
                @endif

                <!-- Accordions / Specifications -->
                <div class="info-accordion">
                    <div class="accordion-item">
                        <div class="accordion-title">
                            <span><i class="fa-solid fa-shield-halved" style="color:#8b0000; margin-right: 8px;"></i> Syarat & Ketentuan Sewa</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="accordion-content">
                            1. Penyewa wajib mengunggah foto KTP asli dan foto selfie dengan KTP.<br>
                            2. Kostum harus dikembalikan dalam kondisi baik dan tidak rusak.<br>
                            3. Durasi rental dihitung per 24 jam kalender sejak pengambilan/pengiriman kostum.
                        </div>
                    </div>

                    <div class="accordion-item">
                        <div class="accordion-title">
                            <span><i class="fa-solid fa-wand-magic-sparkles" style="color:#8b0000; margin-right: 8px;"></i> Perawatan & Standar Higienis</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </div>
                        <div class="accordion-content">
                            Setiap kostum gothic kami dicuci menggunakan metode *dry-clean* profesional dan disterilisasi dengan UV-C sebelum diserahkan kepada penyewa berikutnya demi kenyamanan maksimal.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RELATED PRODUCTS -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
            <div style="margin-top: 80px;">
                <h2 class="section-title">Koleksi Gotik Terkait</h2>
                <div class="product-grid" style="margin-top: 30px;">
                    @foreach($relatedProducts as $relProduct)
                        <div class="product-card">
                            @if($relProduct->status != 'available')
                                <div class="badge-status" style="background: #333;">{{ ucfirst($relProduct->status) }}</div>
                            @else
                                <div class="badge-status">Tersedia</div>
                            @endif

                            @if($relProduct->images->count() > 0)
                                <img src="{{ asset('storage/' . $relProduct->images->first()->image_path) }}" alt="{{ $relProduct->name }}" class="product-img">
                            @else
                                <div class="product-img" style="background:#111; display:flex; align-items:center; justify-content:center; color:#555;">No Image</div>
                            @endif

                            <div class="product-info">
                                <div class="product-category">{{ $relProduct->category->name }}</div>
                                <h3 class="product-name">{{ $relProduct->name }}</h3>
                                <div class="product-price">Rp {{ number_format($relProduct->price_per_day, 0, ',', '.') }} / Hari</div>
                                <div style="margin-top: 15px;">
                                    <a href="{{ route('product.show', $relProduct->slug) }}" class="btn btn-outline" style="display: block; text-align: center; padding: 8px 0; font-size: 12px;">
                                        LIHAT DETAIL
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    const pricePerDay = {{ (int) $product->price_per_day }};
    const checkoutBaseUrl = "{{ route('checkout', $product->id) }}";

    function selectImage(src, element) {
        document.getElementById('mainImage').src = src;
        document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
        element.classList.add('active');
    }

    function updateRentalCalc() {
        const startInput = document.getElementById('calcStartDate');
        const endInput = document.getElementById('calcEndDate');

        if (!startInput || !endInput) return;

        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (end < start) {
            endInput.value = startInput.value;
        }

        const diffTime = Math.abs(new Date(endInput.value) - new Date(startInput.value));
        const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1);

        const totalCost = diffDays * pricePerDay;

        document.getElementById('calcDurationText').innerText = diffDays + ' Hari Sewa';
        document.getElementById('calcTotalText').innerText = 'Rp ' + totalCost.toLocaleString('id-ID');

        const btnCheckout = document.getElementById('btnProceedCheckout');
        if (btnCheckout) {
            btnCheckout.href = checkoutBaseUrl + '?start_date=' + startInput.value + '&end_date=' + endInput.value;
        }
    }
</script>
@endsection
