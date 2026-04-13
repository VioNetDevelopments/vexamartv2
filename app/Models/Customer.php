<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'membership',
        'loyalty_points',
        'total_spent',
        'last_transaction_at',
        'is_active', // Keep this if your table has this column
    ];

    protected $casts = [
        'loyalty_points' => 'integer',
        'total_spent' => 'decimal:2',
        'last_transaction_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationship with transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Add points
    public function addPoints($points)
    {
        $this->increment('loyalty_points', $points);
        return $this->loyalty_points;
    }

    // Deduct points
    public function deductPoints($points)
    {
        if ($this->loyalty_points >= $points) {
            $this->decrement('loyalty_points', $points);
            return true;
        }
        return false;
    }

    // Update membership based on points or total spent
    public function updateMembership()
    {
        if ($this->loyalty_points >= 10000 || $this->total_spent >= 5000000) {
            $this->update(['membership' => 'platinum']);
        } elseif ($this->loyalty_points >= 5000 || $this->total_spent >= 2000000) {
            $this->update(['membership' => 'gold']);
        } elseif ($this->membership !== 'regular') {
            $this->update(['membership' => 'regular']);
        }
    }

    // Scope for active customers only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}