<?php

namespace App\Helpers;

use App\Models\Notification;

class NotificationHelper
{
    public static function create($userId, $type, $title, $message, $icon = 'bell', $color = 'blue', $data = null)
    {
        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'icon' => $icon,
            'color' => $color,
            'data' => $data,
        ]);
    }

    public static function newOrder($userId, $order)
    {
        return self::create(
            $userId,
            'order',
            'Pesanan Baru!',
            'Pesanan #' . $order->invoice_code . ' sebesar Rp ' . number_format($order->grand_total),
            'shopping-bag',
            'blue',
            ['order_id' => $order->id, 'url' => '/admin/orders/' . $order->id]
        );
    }

    public static function flashSaleStarting($userId, $flashSale)
    {
        return self::create(
            $userId,
            'flash_sale',
            'Flash Sale Dimulai!',
            $flashSale->product->name . ' diskon ' . $flashSale->discount_percentage . '%',
            'zap',
            'red',
            ['flash_sale_id' => $flashSale->id, 'url' => '/shop/flash-sales']
        );
    }

    public static function lowStock($userId, $product)
    {
        return self::create(
            $userId,
            'product',
            'Stok Menipis!',
            $product->name . ' tersisa ' . $product->stock . ' unit',
            'alert-triangle',
            'yellow',
            ['product_id' => $product->id, 'url' => '/admin/stock']
        );
    }
}