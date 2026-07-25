@extends('layouts.frontend')

@section('content')
<style>
    .checkout-wrapper {
        max-width: 600px;
        margin: 40px auto;
        background: #111;
        padding: 30px;
        border-radius: 8px;
        border: 1px solid #333;
        color: #fff;
    }

    .checkout-title {
        color: #8b0000;
        text-align: center;
        font-family: serif;
        margin-bottom: 25px;
        border-bottom: 1px solid #333;
        padding-bottom: 15px;
        font-size: 24px;
    }

    .order-summary {
        background: #0a0a0a;
        padding: 15px 20px;
        border-radius: 6px;
        margin-bottom: 30px;
        border-left: 4px solid #8b0000;
    }
    
    .form-group { margin-bottom: 20px; }
    .form-label { display: block; margin-bottom: 8px; font-size: 14px; color: #ccc; }
    
    .form-control {
        width: 100%; padding: 12px 15px; background: #0a0a0a;
        border: 1px solid #444; color: #fff; border-radius: 4px;
        box-sizing: border-box; font-size: 14px;
    }
    
    .form-control:focus { border-color: #8b0000; outline: none; }

    .file-upload-box {
        border: 1px dashed #555; padding: 15px; text-align: center;
        border-radius: 4px; background: #0a0a0a; position: relative; transition: 0.3s;
    }
    
    .file-upload-box input[type="file"] { width: 100%; cursor: pointer; color: #aaa; }
    .img-preview { max-width: 100%; height: auto; margin-top: 15px; border-radius: 4px; display: none; border: 1px solid #444; }

    /* Bagian Data Ekstraksi KTP */
    .extracted-data-box {
        display: none; /* Disembunyikan sebelum upload */
        background: rgba(139, 0, 0, 0.1);
        border: 1px solid #8b0000;
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .btn-submit {
        width: 100%; padding: 15px; background: #8b0000; color: #fff;
        border: none; border-radius: 4px; font-size: 16px; font-weight: bold;
        cursor: pointer; transition: 0.3s; margin-top: 10px;
    }
    
    .btn-submit:hover { background: #a00000; }
    .btn-submit:disabled { background: #555; cursor: not-allowed; }

    /* Area Form Dinamis (Lokasi & WA) */
    .delivery-fields { display: none; }
    
    #scanLoading { display: none; color: #ff4444; font-size: 14px; margin-top: 10px; text-align: center; font-style: italic; }
</style>

<div class="container">
    <div class="checkout-wrapper">
        <h2 class="checkout-title">FORMULIR SEWA</h2>
        
        <div class="order-summary">
            <h4 style="margin: 0 0 8px 0; color: #fff; font-size: 18px;">{{ $product->name ?? 'Korset Asimetris Gothic' }}</h4>
            <p style="margin: 0; color: #aaa; font-size: 15px;">Harga: <span style="color: #ff4444; font-weight: bold;">Rp{{ number_format($product->price_per_day ?? 100000, 0, ',', '.') }} / hari</span></p>
        </div>

        <form action="{{ route('checkout.process', $product->id) }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
            @csrf
            
            <!-- UPLOAD KTP -->
            <div class="form-group">
                <label class="form-label">Upload Foto KTP <span style="color:#ff4444;">*</span></label>
                <div class="file-upload-box">
                    <input type="file" name="foto_ktp" id="fotoKtp" accept="image/jpeg, image/png, image/jpg" required onchange="simulateKTPScan(this)">
                    <div id="scanLoading"><i class="fa-solid fa-spinner fa-spin"></i> Memindai KTP...</div>
                    <img id="previewKtp" class="img-preview" alt="Preview KTP">
                </div>
            </div>

<!-- HASIL SCAN KTP (MUNCUL SETELAH UPLOAD) -->
            <div class="extracted-data-box" id="extractedData">
                <h5 style="margin-top:0; color:#ff4444; font-size: 14px; margin-bottom: 15px;"><i class="fa-solid fa-check-circle"></i> Data KTP Terbaca (Simulasi)</h5>
                
                <div class="form-group">
                    <label class="form-label">NIK</label>
                    <input type="text" name="nik" id="nikInput" class="form-control" readonly required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Nama Sesuai KTP</label>
                    <input type="text" name="name" id="namaInput" class="form-control" readonly required>
                </div>

                <div class="form-group">
                    <label class="form-label">Tempat, Tanggal Lahir</label>
                    <input type="text" name="ttl" id="ttlInput" class="form-control" readonly required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Jenis Kelamin</label>
                    <input type="text" name="gender" id="genderInput" class="form-control" readonly required>
                </div>
            </div>

            <!-- UPLOAD SELFIE KTP -->
            <div class="form-group">
                <label class="form-label">Foto Selfie Memegang KTP <span style="color:#ff4444;">*</span></label>
                <div class="file-upload-box">
                    <input type="file" name="foto_selfie" accept="image/jpeg, image/png, image/jpg" required onchange="previewBasic(this, 'previewSelfie')">
                    <img id="previewSelfie" class="img-preview" alt="Preview Selfie">
                </div>
            </div>

            <!-- METODE PENGIRIMAN -->
            <div class="form-group">
                <label class="form-label">Metode Pengambilan Barang</label>
                <select name="delivery_method" id="deliveryMethod" class="form-control" required onchange="toggleDeliveryFields()">
                    <option value="" disabled selected>-- Pilih Metode --</option>
                    <option value="pickup">Ambil di Toko (Pick-up)</option>
                    <option value="delivery">Antar ke Rumah (Delivery)</option>
                </select>
            </div>

            <!-- FORM DINAMIS: MUNCUL HANYA JIKA DELIVERY -->
            <div class="delivery-fields" id="deliveryFields">
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp <span style="color:#ff4444;">*</span></label>
                    <input type="number" name="whatsapp" id="whatsappInput" class="form-control" placeholder="Contoh: 08123456789">
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap Pengiriman <span style="color:#ff4444;">*</span></label>
                    <textarea name="address" id="addressInput" class="form-control" rows="3" placeholder="Masukkan alamat lengkap RT/RW dan patokan"></textarea>
                </div>
            </div>

            <!-- BARU: METODE PEMBAYARAN -->
            <div class="form-group">
                <label class="form-label">Metode Pembayaran <span style="color:#ff4444;">*</span></label>
                <select name="payment_method" class="form-control" required>
                    <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                    <option value="qris">QRIS (Otomatis)</option>
                    <option value="dana">DANA</option>
                    <option value="ovo">OVO</option>
                    <option value="cod">Bayar di Tempat (COD)</option>
                </select>
            </div>

            <!-- BARU: MENAMPILKAN ERROR JIKA GAGAL SUBMIT -->
            @if ($errors->any())
                <div style="background: rgba(139,0,0,0.2); border: 1px solid #ff4444; color: #ff4444; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                    <strong>Peringatan:</strong> Formulir belum lengkap atau format foto salah.
                    <ul style="margin-top: 5px; margin-bottom: 0; padding-left: 15px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn-submit" id="submitBtn" disabled>LANJUTKAN PEMBAYARAN</button>
        </form>
    </div>
</div>

<script>
    // 1. Logika Toggle Form Delivery
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

    // 2. Preview Biasa untuk Selfie
    function previewBasic(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 3. Simulasi Scan KTP (100% Bohongan dan Selalu Sukses)
    function simulateKTPScan(input) {
        const preview = document.getElementById('previewKtp');
        const loading = document.getElementById('scanLoading');
        const dataBox = document.getElementById('extractedData');
        const btn = document.getElementById('submitBtn');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; preview.style.display = 'block'; }
            reader.readAsDataURL(input.files[0]);

            // Mulai loading pura-pura
            loading.style.display = 'block';
            dataBox.style.display = 'none';
            btn.disabled = true;

            // Tunggu 1.5 detik agar terlihat meyakinkan
            setTimeout(() => {
                loading.style.display = 'none';
                
                // SELALU BERHASIL: Munculkan data palsu
                dataBox.style.display = 'block';
                document.getElementById('nikInput').value = "3170000000000001"; 
                document.getElementById('namaInput').value = "GOTHIC LOVER"; 
                document.getElementById('ttlInput').value = "JAKARTA, 31-10-1999"; 
                document.getElementById('genderInput').value = "PEREMPUAN"; 
                
                // Langsung buka kunci tombol agar bisa diklik
                btn.disabled = false; 
            }, 1500);
        }
    }
</script>
@endsection