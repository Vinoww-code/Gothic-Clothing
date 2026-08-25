@extends('admin.layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_code)

@section('content')
<style>
    .order-grid { display: grid; grid-template-columns: 1.4fr 1fr; gap: 25px; }
    @media (max-width: 900px) { .order-grid { grid-template-columns: 1fr; } }
    
    .section-box { background: #fff; border: 1px solid #e3e6f0; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05); }
    .section-box h3 { margin: 0 0 15px; font-size: 16px; color: #343a40; border-bottom: 1px solid #e9ecef; padding-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    .section-box h3 i { color: #8b0000; }

    .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e9ecef; font-size: 13px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #6c757d; font-weight: 500; }
    .info-value { color: #212529; font-weight: 600; text-align: right; }

    .doc-preview-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px; }
    .doc-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 6px; padding: 12px; text-align: center; }
    .doc-card h5 { margin: 0 0 8px; font-size: 12px; text-transform: uppercase; color: #495057; }
    .doc-img-wrapper { height: 140px; background: #e9ecef; border-radius: 4px; overflow: hidden; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 1px solid #ced4da; transition: border-color 0.2s; }
    .doc-img-wrapper:hover { border-color: #8b0000; }
    .doc-img-wrapper img { width: 100%; height: 100%; object-fit: cover; }

    .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; justify-content: center; align-items: center; padding: 20px; }
    .modal-overlay.active { display: flex; }
    .modal-content { max-width: 700px; width: 100%; background: #fff; border-radius: 8px; padding: 15px; position: relative; border: 1px solid #dee2e6; }
    .modal-content img { width: 100%; max-height: 80vh; object-fit: contain; border-radius: 4px; }
    .modal-close { position: absolute; top: 10px; right: 15px; color: #333; font-size: 24px; cursor: pointer; background: transparent; border: none; }
</style>

<div style="margin-bottom: 20px;">
    <a href="{{ route('admin.orders.index') }}" class="btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Pesanan
    </a>
</div>

<div class="order-grid">
    <!-- LEFT COLUMN: Customer Info, Product, KYC -->
    <div>
        <!-- 1. IDENTITAS PENYEWA & KYC KTP -->
        <div class="section-box">
            <h3><i class="fa-solid fa-id-card"></i> Identitas & Dokumen Verifikasi (KYC)</h3>
            <div class="info-row">
                <span class="info-label">Nama Sesuai KTP</span>
                <span class="info-value">{{ $order->id_card_name ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NIK (Nomor Induk Kependudukan)</span>
                <span class="info-value" style="font-family: monospace;">{{ $order->nik ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tempat, Tanggal Lahir</span>
                <span class="info-value">{{ $order->birth_date_place ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Jenis Kelamin</span>
                <span class="info-value" style="text-transform: capitalize;">{{ $order->gender ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Akun Pemesan</span>
                <span class="info-value">{{ $order->user->name }} ({{ $order->user->email }})</span>
            </div>

            <!-- Dokumentasi Aman Streaming -->
            <div class="doc-preview-grid">
                <div class="doc-card">
                    <h5><i class="fa-solid fa-address-card"></i> Foto KTP Asli</h5>
                    <div class="doc-img-wrapper" onclick="openDocModal('{{ route('documents.order', ['order' => $order->id, 'type' => 'ktp']) }}', 'Foto KTP - {{ $order->id_card_name }}')">
                        <img src="{{ route('documents.order', ['order' => $order->id, 'type' => 'ktp']) }}" alt="KTP">
                    </div>
                    <small style="color: #6c757d; margin-top: 5px; display: block;">Klik untuk perbesar</small>
                </div>
                <div class="doc-card">
                    <h5><i class="fa-solid fa-camera"></i> Selfie Memegang KTP</h5>
                    <div class="doc-img-wrapper" onclick="openDocModal('{{ route('documents.order', ['order' => $order->id, 'type' => 'selfie']) }}', 'Selfie KTP - {{ $order->id_card_name }}')">
                        <img src="{{ route('documents.order', ['order' => $order->id, 'type' => 'selfie']) }}" alt="Selfie KTP">
                    </div>
                    <small style="color: #6c757d; margin-top: 5px; display: block;">Klik untuk perbesar</small>
                </div>
            </div>
        </div>

        <!-- 2. PENGIRIMAN & KONTAK -->
        <div class="section-box">
            <h3><i class="fa-solid fa-truck"></i> Pengantaran & Kontak</h3>
            <div class="info-row">
                <span class="info-label">Metode Pengambilan</span>
                <span class="info-value" style="text-transform: uppercase;">
                    <span class="status-badge" style="background: #e3f2fd; color: #0d6efd;">{{ $order->delivery_method }}</span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Nomor WhatsApp</span>
                <span class="info-value">
                    @if($order->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $order->whatsapp) }}" target="_blank" style="color: #28a745; text-decoration: none;">
                            <i class="fa-brands fa-whatsapp"></i> {{ $order->whatsapp }}
                        </a>
                    @else
                        -
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Alamat Pengiriman</span>
                <span class="info-value" style="max-width: 60%; font-weight: normal; color: #495057;">
                    {{ $order->shipping_address ?? 'Ambil langsung di butik' }}
                </span>
            </div>
            @if($order->notes)
                <div class="info-row">
                    <span class="info-label">Catatan Pesanan</span>
                    <span class="info-value" style="max-width: 60%; font-weight: normal; color: #495057;">
                        {{ $order->notes }}
                    </span>
                </div>
            @endif
        </div>

        <!-- 3. PRODUK YANG DISEWA -->
        <div class="section-box">
            <h3><i class="fa-solid fa-shirt"></i> Kostum Gothic yang Disewa</h3>
            @if($order->product)
                <div style="display: flex; gap: 15px; align-items: center;">
                    @if($order->product->images->count() > 0)
                        <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" alt="" style="width: 75px; height: 75px; object-fit: cover; border-radius: 6px; border: 1px solid #dee2e6;">
                    @endif
                    <div>
                        <h4 style="margin: 0 0 5px; font-size: 16px; color: #212529;">{{ $order->product->name }}</h4>
                        <div style="color: #8b0000; font-weight: 600; font-size: 12px; text-transform: uppercase;">
                            {{ $order->product->category->name ?? 'Gothic Costume' }}
                        </div>
                        <div style="color: #6c757d; font-size: 13px; margin-top: 4px;">
                            Tarif Sewa: Rp {{ number_format($order->price_per_day, 0, ',', '.') }} / hari
                        </div>
                    </div>
                </div>
            @else
                <p style="color: #6c757d;">Produk ini telah dihapus dari sistem.</p>
            @endif
        </div>
    </div>

    <!-- RIGHT COLUMN: Status & Update Form -->
    <div>
        <!-- 1. SUMMARY KEUANGAN & PEMBAYARAN -->
        <div class="section-box">
            <h3><i class="fa-solid fa-receipt"></i> Rincian Pembayaran</h3>
            <div class="info-row">
                <span class="info-label">Kode Pesanan</span>
                <span class="info-value" style="font-family: monospace; color: #212529;">{{ $order->order_code }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Pemesanan</span>
                <span class="info-value">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
            </div>
            <div class="info-row">
                <span class="info-label">Periode Sewa</span>
                <span class="info-value">
                    @if($order->start_date && $order->end_date)
                        {{ $order->start_date->format('d M Y') }} s/d {{ $order->end_date->format('d M Y') }}
                    @else
                        {{ $order->rental_days }} Hari
                    @endif
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Durasi Hari</span>
                <span class="info-value">{{ $order->rental_days }} Hari</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode Pembayaran</span>
                <span class="info-value" style="text-transform: uppercase;">{{ $order->payment_method }}</span>
            </div>
            <div class="info-row" style="font-size: 15px; padding-top: 10px;">
                <span class="info-label" style="font-weight: bold; color: #212529;">Total Tagihan</span>
                <span class="info-value" style="color: #28a745; font-size: 18px; font-weight: bold;">
                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                </span>
            </div>
            <div class="info-row" style="margin-top: 10px;">
                <span class="info-label">Status Bayar Saat Ini</span>
                <span class="status-badge badge-{{ $order->payment_status }}">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Status Sewa Saat Ini</span>
                <span class="status-badge badge-{{ $order->order_status }}">
                    {{ $order->order_status_label }}
                </span>
            </div>

            @if($order->order_status === 'cancelled')
                <div style="background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 6px; padding: 12px; margin-top: 15px;">
                    <div style="color: #721c24; font-weight: bold; font-size: 13px;">
                        <i class="fa-solid fa-ban"></i> Informasi Pembatalan:
                    </div>
                    <div style="color: #721c24; font-size: 13px; margin-top: 4px;">
                        "{{ $order->cancellation_reason ?? 'Dibatalkan oleh pelanggan' }}"
                    </div>
                    @if($order->cancelled_at)
                        <div style="color: #6c757d; font-size: 11px; margin-top: 4px;">
                            Waktu Batal: {{ $order->cancelled_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- 2. FORM UPDATE STATUS PESANAN -->
        <div class="section-box">
            <h3><i class="fa-solid fa-pen-to-square"></i> Perbarui Status Pesanan</h3>
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="form-group">
                    <label for="payment_status">Status Pembayaran</label>
                    <select name="payment_status" id="payment_status" class="form-control">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>PENDING (Belum / Menunggu Bayar)</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>PAID (Sudah Lunas)</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>FAILED (Gagal / Batal)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="order_status">Tahapan Status Pesanan / Sewa</label>
                    <select name="order_status" id="order_status" class="form-control">
                        <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>1. PENDING (Menunggu Pembayaran & Verifikasi)</option>
                        <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>2. CONFIRMED (Pembayaran Disetujui - Belum Dikemas)</option>
                        <option value="packing" {{ $order->order_status == 'packing' ? 'selected' : '' }}>3. PACKING (Sedang Dikemas & Disiapkan)</option>
                        <option value="shipping" {{ $order->order_status == 'shipping' ? 'selected' : '' }}>4. SHIPPING (Sedang Dikirim / Siap Diambil)</option>
                        <option value="rented" {{ $order->order_status == 'rented' ? 'selected' : '' }}>5. RENTED (Sedang Disewa / Masa Sewa Aktif)</option>
                        <option value="completed" {{ $order->order_status == 'completed' ? 'selected' : '' }}>6. COMPLETED (Selesai - Kostum Telah Kembali)</option>
                        <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>7. CANCELLED (Dibatalkan)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="admin_notes">Tambahkan Catatan Admin (Opsional)</label>
                    <textarea name="admin_notes" id="admin_notes" rows="3" class="form-control" placeholder="Contoh: KTP valid, kostum sedang dikemas, resi kurir #123..."></textarea>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; justify-content: center; padding: 10px;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Pembaruan Status
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Lightbox Modal for Documents -->
<div id="docModal" class="modal-overlay" onclick="closeDocModal()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeDocModal()">&times;</button>
        <h4 id="modalTitle" style="color: #333; margin: 0 0 10px; text-align: center; font-size: 15px;"></h4>
        <img id="modalImg" src="" alt="Pratinjau Dokumen">
    </div>
</div>

<script>
    function openDocModal(src, title) {
        document.getElementById('modalImg').src = src;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('docModal').classList.add('active');
    }

    function closeDocModal() {
        document.getElementById('docModal').classList.remove('active');
    }
</script>
@endsection
