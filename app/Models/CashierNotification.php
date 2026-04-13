<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashierNotification extends Model
{
    protected $fillable = [
        'user_id', 'type', 'title', 'message', 'data', 'read_at'
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function isUnread()
    {
        return $this->read_at === null;
    }

    public static function createPaymentNotification($userId, $transaction)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'payment_received',
            'title' => 'Pembayaran Diterima',
            'message' => "Transaksi {$transaction->invoice_code} sebesar Rp " . number_format($transaction->grand_total),
            'data' => [
                'transaction_id' => $transaction->id,
                'invoice_code' => $transaction->invoice_code,
                'amount' => $transaction->grand_total,
                'payment_method' => $transaction->payment_method,
            ]
        ]);
    }
}