<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'code',
        'name',
        'phone',
        'external_id',
        'checkout_url',
        'barcode_id',
        'payment_type',
        'payment_status',
        'subtotal',
        'ppn',
        'total',
    ];


    public function barcode()
    {
        return $this->belongsTo(Barcode::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}