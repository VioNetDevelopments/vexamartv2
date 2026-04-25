<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashierShift extends Model
{
    protected $fillable = [
        'user_id',
        'started_at',
        'ended_at',
        'starting_cash',
        'expected_cash',
        'actual_cash',
        'cash_shortage',
        'cash_deposit',
        'closing_notes',
        'status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'starting_cash' => 'decimal:2',
        'expected_cash' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'cash_shortage' => 'decimal:2',
        'cash_deposit' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id', 'user_id')
            ->whereBetween('created_at', [$this->started_at, $this->ended_at ?? now()]);
    }

    public function isOpen(): bool
    {
        return $this->status === 'open';
    }

    public function calculateExpectedCash(): void
    {
        $totalSales = $this->transactions()
            ->where('payment_method', 'cash')
            ->sum('grand_total');

        $this->attributes['expected_cash'] = $this->starting_cash + $totalSales;
        $this->attributes['cash_shortage'] = $this->actual_cash - $this->attributes['expected_cash'];
    }
}