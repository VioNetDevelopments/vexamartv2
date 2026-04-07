<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrisPayment extends Model
{
    protected $fillable = [
        'invoice_code', 'amount', 'status', 'qr_data', 
        'payment_gateway_ref', 'paid_at', 'expired_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
        'qr_data' => 'array',
    ];

    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'invoice_code', 'invoice_code');
    }
}