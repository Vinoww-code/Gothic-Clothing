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
        'total_amount',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'price_per_day' => 'integer',
        'rental_days' => 'integer',
        'total_amount' => 'integer',
    ];

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
