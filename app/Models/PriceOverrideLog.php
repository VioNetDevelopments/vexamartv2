<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceOverrideLog extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'original_price',
        'new_price',
        'reason',
        'approved_by'
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'new_price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}