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
            'title' => 'Duit Masuk, King! 💸',
            'message' => "Transaksi {$transaction->invoice_code} senilai Rp " . number_format($transaction->grand_total, 0, ',', '.') . " udah lunas, King!",
            'data' => [
                'transaction_id' => $transaction->id,
                'invoice_code' => $transaction->invoice_code,
                'amount' => $transaction->grand_total,
            ]
        ]);
    }

    public static function createLowStockNotification($userId, $product)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'low_stock',
            'title' => 'Stok Tipis, King! ⚠️',
            'message' => "Barang {$product->name} sisa {$product->stock} nih, cepetan restok King!",
            'data' => [
                'product_id' => $product->id,
                'stock' => $product->stock,
            ]
        ]);
    }

    public static function createProductNotification($userId, $product, $action = 'updated')
    {
        $titles = [
            'updated' => 'Produk Diperbarui, King! ✨',
            'created' => 'Produk Baru, King! 🆕',
            'deleted' => 'Produk Dihapus, King! 🗑️',
        ];

        $messages = [
            'updated' => "Data produk {$product->name} baru aja kita permak, King!",
            'created' => "Ada barang baru nih: {$product->name}, cekidot King!",
            'deleted' => "Produk {$product->name} udah ilang dari sistem, King!",
        ];

        return self::create([
            'user_id' => $userId,
            'type' => 'product_' . $action,
            'title' => $titles[$action] ?? 'Info Produk, King!',
            'message' => $messages[$action] ?? "Ada perubahan di produk {$product->name}, King!",
            'data' => ['product_id' => $product->id]
        ]);
    }
}