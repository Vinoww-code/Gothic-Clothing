<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_code',
        'user_id',
        'product_id',
        'nik',
        'id_card_name',
        'birth_date_place',
        'gender',
        'id_card_path',
        'selfie_path',
        'delivery_method',
        'whatsapp',
        'shipping_address',
        'payment_method',
        'payment_status',
        'order_status',
        'price_per_day',
        'rental_days',
        'start_date',
        'end_date',
        'total_amount',
        'notes',
        'cancellation_reason',
        'cancelled_at',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price_per_day' => 'integer',
        'rental_days' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'total_amount' => 'integer',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Check if the order can still be cancelled by the customer.
     * Allowed only when order is 'pending' or 'confirmed' (not yet packing/shipping/completed/cancelled).
     */
    public function isCancellable(): bool
    {
        return in_array($this->order_status, ['pending', 'confirmed']);
    }

    /**
     * Get user-friendly Indonesian label for order_status.
     */
    public function getOrderStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'Menunggu Pembayaran & Verifikasi',
            'confirmed' => 'Pembayaran Disetujui (Belum Dikemas)',
            'packing' => 'Sedang Dikemas & Disiapkan',
            'shipping' => 'Sedang Dikirim / Siap Diambil',
            'rented' => 'Sedang Disewa (Digunakan)',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => strtoupper($this->order_status),
        };
    }

    /**
     * Get CSS badge class for order_status.
     */
    public function getOrderStatusClassAttribute(): string
    {
        return match ($this->order_status) {
            'pending' => 'status-pending',
            'confirmed' => 'status-confirmed',
            'packing' => 'status-packing',
            'shipping' => 'status-shipping',
            'rented' => 'status-rented',
            'completed' => 'status-completed',
            'cancelled' => 'status-cancelled',
            default => 'status-pending',
        };
    }

    /**
     * Get the user who placed the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product associated with this order.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
