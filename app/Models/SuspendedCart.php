<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuspendedCart extends Model
{
    protected $fillable = [
        'suspension_code',
        'user_id',
        'customer_id',
        'items',
        'subtotal',
        'discount',
        'tax',
        'total',
        'notes',
        'expires_at'
    ];

    protected $casts = [
        'items' => 'array',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'expires_at' => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($cart) {
            $cart->suspension_code = 'HOLD-' . strtoupper(uniqid());
            $cart->expires_at = now()->addHours(24); // Auto-cancel after 24 hours
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }
}