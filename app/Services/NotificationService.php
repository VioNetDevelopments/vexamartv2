<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Trigger low stock notification if necessary
     */
    public static function checkAndNotifyLowStock($product)
    {
        if ($product->stock <= $product->min_stock) {
            // Check if we already notified for this today to avoid spamming
            $alreadyNotified = Notification::where('type', 'low_stock')
                ->where('data->product_id', $product->id)
                ->whereDate('created_at', today())
                ->exists();

            if (!$alreadyNotified) {
                return self::lowStock($product);
            }
        }
        return null;
    }

    public static function lowStock($product, $users = null)
    {
        if (!$users) {
            $users = User::whereIn('role', ['admin', 'owner', 'cashier'])->get();
        }

        $now = now();
        $notifications = [];
        $data = json_encode([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'current_stock' => $product->stock,
            'min_stock' => $product->min_stock,
            'url' => route('admin.stock.index', ['search' => $product->name]),
        ]);

        foreach ($users as $user) {
            $notifications[] = [
                'user_id' => $user->id,
                'type' => 'low_stock',
                'title' => 'Stok Menipis!',
                'message' => "Stok {$product->name} tinggal {$product->stock} unit. Segera restock!",
                'data' => $data,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($notifications)) {
            return Notification::insert($notifications);
        }
        return null;
    }

    public static function restock($product, $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }

        if (!$user) return null;

        return Notification::create([
            'user_id' => $user->id,
            'type' => 'restock',
            'title' => 'Produk Direstock',
            'message' => "Produk {$product->name} telah direstock menjadi {$product->stock} unit.",
            'data' => [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'new_stock' => $product->stock,
                'url' => route('admin.stock.index', ['search' => $product->name]),
            ],
        ]);
    }

    public static function paymentReceived($transaction, $userId)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => 'payment_received',
            'title' => 'Pembayaran Diterima',
            'message' => "Transaksi {$transaction->invoice_code} sebesar Rp " . number_format($transaction->grand_total),
            'data' => [
                'transaction_id' => $transaction->id,
                'invoice_code' => $transaction->invoice_code,
                'amount' => $transaction->grand_total,
                'payment_method' => $transaction->payment_method,
                'url' => route('admin.transactions.show', $transaction->id),
            ]
        ]);
    }

    public static function newTransaction($transaction, $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }

        $targets = User::whereIn('role', ['admin', 'owner'])->get();
        if ($user && $user->role === 'cashier' && !$targets->contains($user)) {
            $targets->push($user);
        }

        $now = now();
        $notifications = [];
        $data = json_encode([
            'transaction_id' => $transaction->id,
            'invoice_code' => $transaction->invoice_code,
            'amount' => $transaction->grand_total,
            'url' => route('admin.transactions.show', $transaction->id),
        ]);

        foreach ($targets as $target) {
            $notifications[] = [
                'user_id' => $target->id,
                'type' => 'new_transaction',
                'title' => 'Transaksi Berhasil',
                'message' => "Invoice {$transaction->invoice_code} senilai Rp " . number_format($transaction->grand_total) . " telah diproses.",
                'data' => $data,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    public static function outOfStock($product)
    {
        $users = User::whereIn('role', ['admin', 'owner', 'cashier'])->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'out_of_stock',
                'title' => 'Stok Habis!',
                'message' => "Perhatian! Produk {$product->name} telah habis (0 stok).",
                'data' => [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'url' => route('admin.products.index', ['search' => $product->name]),
                ],
            ]);
        }
    }

    public static function customerRegistered($customer)
    {
        $users = User::whereIn('role', ['admin', 'owner'])->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'customer_registered',
                'title' => 'Pelanggan Baru',
                'message' => "Pelanggan {$customer->name} baru saja terdaftar dalam sistem.",
                'data' => [
                    'customer_id' => $customer->id,
                    'customer_name' => $customer->name,
                    'url' => route('admin.customers.index', ['search' => $customer->name]),
                ],
            ]);
        }
    }

    public static function priceUpdated($product, $oldPrice, $newPrice)
    {
        $users = User::whereIn('role', ['admin', 'owner', 'cashier'])->get();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'price_updated',
                'title' => 'Perubahan Harga',
                'message' => "Harga {$product->name} berubah dari Rp " . number_format($oldPrice) . " menjadi Rp " . number_format($newPrice),
                'data' => [
                    'product_id' => $product->id,
                    'old_price' => $oldPrice,
                    'new_price' => $newPrice,
                    'url' => route('admin.products.index', ['search' => $product->name]),
                ],
            ]);
        }
    }

    public static function stockAdjustment($product, $adjustment)
    {
        $users = User::whereIn('role', ['admin', 'owner'])->get();
        $type = $adjustment > 0 ? 'bertambah' : 'berkurang';
        $absAdjustment = abs($adjustment);

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'stock_adjustment',
                'title' => 'Penyesuaian Stok',
                'message' => "Stok {$product->name} telah {$type} sebanyak {$absAdjustment} unit secara manual.",
                'data' => [
                    'product_id' => $product->id,
                    'adjustment' => $adjustment,
                    'url' => route('admin.stock.index', ['search' => $product->name]),
                ],
            ]);
        }
    }

    public static function productDeleted($product, $user = null)
    {
        if (!$user) {
            $user = auth()->user();
        }

        $targets = User::whereIn('role', ['admin', 'owner'])->get();
        
        $now = now();
        $notifications = [];
        $data = json_encode([
            'product_id' => $product->id,
            'product_name' => $product->name,
            'deleted_by' => $user ? $user->name : 'System',
        ]);

        foreach ($targets as $target) {
            $notifications[] = [
                'user_id' => $target->id,
                'type' => 'product_deleted',
                'title' => 'Produk Dihapus',
                'message' => "Produk {$product->name} telah dihapus dari sistem oleh " . ($user ? $user->name : 'System') . ".",
                'data' => $data,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($notifications)) {
            Notification::insert($notifications);
        }
    }

    public static function getUnreadCount($userId)
    {
        return Notification::where('user_id', $userId)->unread()->count();
    }

    public static function getUnread($userId, $limit = 10)
    {
        return Notification::where('user_id', $userId)
            ->unread()
            ->latest()
            ->limit($limit)
            ->get();
    }
}