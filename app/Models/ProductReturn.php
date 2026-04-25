<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReturn extends Model
{
    protected $fillable = [
        'return_code',
        'transaction_id',
        'user_id',
        'product_id',
        'quantity',
        'price',
        'total',
        'reason',
        'status',
        'notes'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($return) {
            $return->return_code = 'RET-' . strtoupper(uniqid());
        });

        static::created(function ($return) {
            // Restore stock
            $return->product->increment('stock', $return->quantity);
        });
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}