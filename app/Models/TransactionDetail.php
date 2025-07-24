<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    //
    protected $fillable = [
        'food_id',
        'quantity',
        'price',
        'subtotal',
        'transaction_id',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}