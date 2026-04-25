<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends Model
{
    protected $fillable = [
        'name',
        'price',
        'duration_days',
        'benefits',
        'discount_percentage',
        'loyalty_points_multiplier',
        'is_active'
    ];

    protected $casts = [
        'benefits' => 'array',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}