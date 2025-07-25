<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    //
    protected $fillable = [
        'name',
        'price',
        'images',
        'description',
        'discount_price',
        'discount_percentage',
        'is_promo',
        'category_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
