<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    // Cukup gunakan fillable (slug sudah ditambahkan)
    protected $fillable = [
        'name', 
        'slug', 
        'type', 
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}