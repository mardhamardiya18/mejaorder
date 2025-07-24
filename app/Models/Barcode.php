<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barcode extends Model
{
    protected $fillable = [
        'table_number',
        'images',
        'qr_value',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}