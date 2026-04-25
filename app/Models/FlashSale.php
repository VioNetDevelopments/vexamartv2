<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class FlashSale extends Model
{
    protected $fillable = [
        'title',
        'description',
        'product_id',
        'discount_percentage',
        'max_quantity',
        'sold_quantity',
        'starts_at',
        'ends_at',
        'is_active'
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isRunning(): bool
    {
        return $this->is_active
            && $this->starts_at <= now()
            && $this->ends_at > now()
            && $this->sold_quantity < $this->max_quantity;
    }

    public function getIsRunningAttribute(): bool
    {
        return $this->isRunning();
    }

    public function getProgressAttribute(): float
    {
        return ($this->sold_quantity / $this->max_quantity) * 100;
    }

    public function getTimeRemainingAttribute(): string
    {
        $diff = now()->diff($this->ends_at);
        
        if ($diff->d > 0) return $diff->d . ' hari lagi';
        if ($diff->h > 0) return $diff->h . ' jam lagi';
        if ($diff->i > 0) return $diff->i . ' menit lagi';
        return 'Segera Berakhir';
    }
}