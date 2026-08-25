@extends('layouts.frontend')

@section('content')
<style>
    .checkout-wrapper {
        max-width: 650px;
        margin: 40px auto;
        background: #111;
        padding: 35px;
        border-radius: 10px;
        border: 1px solid #2a2a2a;
        color: #fff;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.6);
    }

    .checkout-title {
        color: #fff;
        text-align: center;
        font-family: serif;
        margin-bottom: 25px;
        border-bottom: 1px solid #333;
        padding-bottom: 15px;
        font-size: 24px;
        letter-spacing: 1px;
    }

    .order-summary {
        background: #0a0a0a;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border-left: 4px solid #8b0000;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .order-summary-img {
        width: 80px;
        height: 80px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #333;
    }
    
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-size: 13px; font-weight: 600; color: #ccc; letter-spacing: 0.5px; }
    
    .form-control {
        width: 100%; padding: 12px 15px; background: #0a0a0a;
        border: 1px solid #333; color: #fff; border-radius: 6px;
        box-sizing: border-box; font-size: 14px; transition: 0.3s;
    }
    
    .form-control:focus { border-color: #8b0000; outline: none; box-shadow: 0 0 0 2px rgba(139, 0, 0, 0.3); }

    .dates-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 15px;
    }
    @media (max-width: 500px) { .dates-grid { grid-template-columns: 1fr; } }

    .file-upload-box {
        border: 1px dashed #555; padding: 20px; text-align: center;
        border-radius: 6px; background: #0a0a0a; position: relative; transition: 0.3s;
    }
    
    .file-upload-box:hover { border-color: #8b0000; }
    .file-upload-box input[type="file"] { width: 100%; cursor: pointer; color: #aaa; }
    .img-preview { max-width: 100%; max-height: 200px; margin-top: 15px; border-radius: 6px; display: none; border: 1px solid #444; object-fit: contain; }

    /* Bagian Data Ekstraksi KTP */
    .extracted-data-box {
        background: rgba(139, 0, 0, 0.08);
        border: 1px solid rgba(139, 0, 0, 0.4);
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 25px;
    }

    .btn-submit {
        width: 100%; padding: 15px; background: #8b0000; color: #fff;
        border: none; border-radius: 6px; font-size: 15px; font-weight: bold;
        letter-spacing: 1px; cursor: pointer; transition: 0.3s; margin-top: 15px;
    }
    
    .btn-submit:hover { background: #a00000; transform: translateY(-1px); }
    .btn-submit:disabled { background: #444; color: #888; cursor: not-allowed; transform: none; }

    /* Area Form Dinamis (Lokasi & WA) */
    .delivery-fields { display: none; }
    
    #scanLoading { display: none; color: #ff6b6b; font-size: 14px; margin-top: 10px; text-align: center; font-style: italic; }
</style>

<div class="container section-padding" style="padding-top: 110px;">
    <div class="checkout-wrapper">
        <h2 class="checkout-title"><i class="fa-solid fa-file-invoice" style="margin-right: 8px; color: #8b0000;"></i> FORMULIR PENYEWAAN</h2>
        
        <!-- SUMMARY PRODUK -->
        <div class="order-summary">
            @if($product->images->count() > 0)
                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" class="order-summary-img">
            @else
                <div class="order-summary-img" style="background:#222; display:flex; align-items:center; justify-content:center; color:#666;"><i class="fa-solid fa-shirt"></i></div>
            @endif
            <div style="flex: 1;">
                <h4 style="margin: 0 0 6px 0; color: #fff; font-size: 17px;">{{ $product->name }}</h4>
                <div style="color: #888; font-size: 13px; margin-bottom: 6px;">Kategori: {{ $product->category->name ?? 'Gothic' }}</div>
                <div style="font-size: 14px; color: #aaa;">Tarif Sewa: <span style="color: #ff4444; font-weight: bold;">Rp {{ number_format($product->price_per_day, 0, ',', '.') }}</span> / hari</div>
            </div>
        </div>

        <form action="{{ route('checkout.process', $product->id) }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
            @csrf
            
            <!-- RENTANG TANGGAL SEWA (DATE RANGE) -->
            <div class="dates-grid">
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Mulai Sewa <span style="color:#ff4444;">*</span></label>
                    <input type="date" name="start_date" id="startDate" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('start_date', request('start_date', date('Y-m-d'))) }}" onchange="handleDateChange()">
                    @error('start_date') <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Tanggal Selesai Sewa <span style="color:#ff4444;">*</span></label>
                    <input type="date" name="end_date" id="endDate" class="form-control" min="{{ date('Y-m-d') }}" value="{{ old('end_date', request('end_date', date('Y-m-d', strtotime('+1 day')))) }}" onchange="handleDateChange()">
                    @error('end_date') <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <input type="hidden" name="rental_days" id="rentalDays" value="{{ old('rental_days', 2) }}">

            <!-- UPLOAD KTP -->
            <div class="form-group">
                <label class="form-label">Upload Foto KTP Asli <span style="color:#ff4444;">*</span></label>
                <div class="file-upload-box">
                    <input type="file" name="foto_ktp" id="fotoKtp" accept="image/jpeg, image/png, image/jpg" required onchange="simulateKTPScan(this)">
                    <div id="scanLoading"><i class="fa-solid fa-spinner fa-spin"></i> Memindai & memverifikasi KTP...</div>
                    <img id="previewKtp" class="img-preview" alt="Preview KTP">
                </div>
                @error('foto_ktp')
                    <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- HASIL SCAN KTP -->
            <div class="extracted-data-box" id="extractedData" style="{{ (old('nik') || old('name')) ? 'display: block;' : 'display: none;' }}">
                <h5 style="margin-top:0; color:#ff6b6b; font-size: 14px; margin-bottom: 15px;"><i class="fa-solid fa-shield-halved"></i> Data Identitas KTP</h5>
                
                <div class="form-group">
                    <label class="form-label">NIK (Nomor Induk Kependudukan) <span style="color:#ff4444;">*</span></label>
                    <input type="text" name="nik" id="nikInput" value="{{ old('nik') }}" class="form-control" required placeholder="Contoh: 3171010101990001">
                    @error('nik') <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div> @enderror
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap (Sesuai KTP) <span style="color:#ff4444;">*</span></label>
                    <input type="text" name="name" id="namaInput" value="{{ old('name', Auth::user()->name ?? '') }}" class="form-control" required placeholder="Nama lengkap">
                    @error('name') <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tempat, Tanggal Lahir <span style="color:#ff4444;">*</span></label>
                    <input type="text" name="ttl" id="ttlInput" value="{{ old('ttl') }}" class="form-control" required placeholder="Contoh: JAKARTA, 31-10-1999">
                    @error('ttl') <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Jenis Kelamin <span style="color:#ff4444;">*</span></label>
                    <select name="gender" id="genderInput" class="form-control" required>
                        <option value="LAKI-LAKI" {{ old('gender') == 'LAKI-LAKI' ? 'selected' : '' }}>LAKI-LAKI</option>
                        <option value="PEREMPUAN" {{ old('gender', 'PEREMPUAN') == 'PEREMPUAN' ? 'selected' : '' }}>PEREMPUAN</option>
                    </select>
                    @error('gender') <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- UPLOAD SELFIE KTP -->
            <div class="form-group">
                <label class="form-label">Foto Selfie Memegang KTP <span style="color:#ff4444;">*</span></label>
                <div class="file-upload-box">
                    <input type="file" name="foto_selfie" id="fotoSelfie" accept="image/jpeg, image/png, image/jpg" required onchange="previewBasic(this, 'previewSelfie')">
                    <img id="previewSelfie" class="img-preview" alt="Preview Selfie">
                </div>
                @error('foto_selfie')
                    <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- METODE PENGIRIMAN -->
            <div class="form-group">
                <label class="form-label">Metode Pengambilan Barang <span style="color:#ff4444;">*</span></label>
                <select name="delivery_method" id="deliveryMethod" class="form-control" required onchange="toggleDeliveryFields()">
                    <option value="" disabled {{ old('delivery_method') ? '' : 'selected' }}>-- Pilih Metode Pengambilan --</option>
                    <option value="pickup" {{ old('delivery_method') == 'pickup' ? 'selected' : '' }}>Ambil di Butik / Toko (Pick-up)</option>
                    <option value="delivery" {{ old('delivery_method') == 'delivery' ? 'selected' : '' }}>Antar Langsung ke Alamat (Delivery)</option>
                </select>
                @error('delivery_method')
                    <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- FORM DINAMIS: DELIVERY -->
            <div class="delivery-fields" id="deliveryFields" style="{{ old('delivery_method') == 'delivery' ? 'display: block;' : '' }}">
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp Aktif <span style="color:#ff4444;">*</span></label>
                    <input type="text" name="whatsapp" id="whatsappInput" value="{{ old('whatsapp') }}" class="form-control" placeholder="Contoh: 081234567890">
                    @error('whatsapp') <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap Pengiriman <span style="color:#ff4444;">*</span></label>
                    <textarea name="address" id="addressInput" class="form-control" rows="3" placeholder="Masukkan alamat lengkap pengantaran beserta catatan patokan lokasi...">{{ old('address') }}</textarea>
                    @error('address') <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- METODE PEMBAYARAN -->
            <div class="form-group">
                <label class="form-label">Metode Pembayaran <span style="color:#ff4444;">*</span></label>
                <select name="payment_method" class="form-control" required>
                    <option value="" disabled {{ old('payment_method') ? '' : 'selected' }}>-- Pilih Metode Pembayaran --</option>
                    <option value="qris" {{ old('payment_method', 'qris') == 'qris' ? 'selected' : '' }}>QRIS (Instant QR & E-Wallet)</option>
                    <option value="dana" {{ old('payment_method') == 'dana' ? 'selected' : '' }}>DANA Transfer</option>
                    <option value="ovo" {{ old('payment_method') == 'ovo' ? 'selected' : '' }}>OVO Transfer</option>
                    <option value="cod" {{ old('payment_method') == 'cod' ? 'selected' : '' }}>Bayar di Tempat (COD / Kasir)</option>
                </select>
                @error('payment_method')
                    <div style="color: #ff6b6b; font-size: 12px; margin-top: 5px;">{{ $message }}</div>
                @enderror
            </div>

            <!-- CATATAN TAMBAHAN (OPTIONAL) -->
            <div class="form-group">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Ukuran khusus atau catatan untuk staf...">{{ old('notes') }}</textarea>
            </div>

            <!-- TOTAL SUMMARY CARD -->
            <div style="background: #000; border: 1px solid #333; padding: 15px 20px; border-radius: 6px; margin-top: 25px; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <span style="color: #aaa; font-size: 13px; display: block;">Total Estimasi Pembayaran:</span>
                    <span id="durationSummary" style="color: #64748b; font-size: 12px;">2 Hari Sewa</span>
                </div>
                <span id="totalDisplay" style="color: #4ade80; font-size: 20px; font-weight: bold;">
                    Rp {{ number_format($product->price_per_day * 2, 0, ',', '.') }}
                </span>
            </div>

            <!-- GLOBAL ERRORS -->
            @if ($errors->any())
                <div style="background: rgba(139,0,0,0.25); border: 1px solid #ff4444; color: #ff6b6b; padding: 12px; border-radius: 6px; margin-top: 20px; font-size: 13px;">
                    <strong><i class="fa-solid fa-triangle-exclamation"></i> Harap periksa kembali data isian:</strong>
                    <ul style="margin-top: 5px; margin-bottom: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn-submit" id="submitBtn" {{ (old('nik') || old('name')) ? '' : 'disabled' }}>
                <i class="fa-solid fa-lock" style="margin-right: 6px;"></i> KONFIRMASI & LANJUTKAN PEMBAYARAN
            </button>
        </form>
    </div>
</div>

<script>
    const pricePerDay = {{ (int) $product->price_per_day }};

    function handleDateChange() {
        const startInput = document.getElementById('startDate');
        const endInput = document.getElementById('endDate');

        if (!startInput.value || !endInput.value) return;

        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (end < start) {
            endInput.value = startInput.value;
        }

        const diffTime = Math.abs(new Date(endInput.value) - new Date(startInput.value));
        const diffDays = Math.max(1, Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1);

        document.getElementById('rentalDays').value = diffDays;
        document.getElementById('durationSummary').innerText = diffDays + ' Hari Sewa';

        const total = pricePerDay * diffDays;
        document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    function toggleDeliveryFields() {
        const method = document.getElementById('deliveryMethod').value;
        const deliveryFields = document.getElementById('deliveryFields');
        const waInput = document.getElementById('whatsappInput');
        const addressInput = document.getElementById('addressInput');

        if (method === 'delivery') {
            deliveryFields.style.display = 'block';
            waInput.required = true;
            addressInput.required = true;
        } else {
            deliveryFields.style.display = 'none';
            waInput.required = false;
            addressInput.required = false;
        }
    }

    function previewBasic(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function simulateKTPScan(input) {
        const preview = document.getElementById('previewKtp');
        const loading = document.getElementById('scanLoading');
        const dataBox = document.getElementById('extractedData');
        const btn = document.getElementById('submitBtn');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);

            loading.style.display = 'block';
            dataBox.style.display = 'none';
            btn.disabled = true;

            setTimeout(() => {
                loading.style.display = 'none';
                dataBox.style.display = 'block';
                
                // Populate default simulation data if inputs are empty
                const nikInput = document.getElementById('nikInput');
                if (!nikInput.value) {
                    nikInput.value = "31710" + Math.floor(10000000000 + Math.random() * 90000000000);
                }
                const ttlInput = document.getElementById('ttlInput');
                if (!ttlInput.value) {
                    ttlInput.value = "JAKARTA, 31-10-1999";
                }
                
                btn.disabled = false; 
            }, 1000);
        }
    }

    // Auto-calculate on initial load
    document.addEventListener('DOMContentLoaded', () => {
        handleDateChange();
        toggleDeliveryFields();
    });
</script>
@endsection