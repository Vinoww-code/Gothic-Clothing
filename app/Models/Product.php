<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

   protected $fillable = [
        'name', 
        'slug', 
        'category_id', 
        'description', 
        'price_per_day', 
        'status', 
        'is_best_seller',
        'sizes',   
        'colors'   
    ];

  protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}