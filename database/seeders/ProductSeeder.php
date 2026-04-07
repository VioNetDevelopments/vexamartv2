<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Beras Premium 5kg', 'buy' => 60000, 'sell' => 75000, 'stock' => 50, 'cat' => 4],
            ['name' => 'Minyak Goreng 2L', 'buy' => 35000, 'sell' => 42000, 'stock' => 30, 'cat' => 4],
            ['name' => 'Gula Pasir 1kg', 'buy' => 14000, 'sell' => 18000, 'stock' => 100, 'cat' => 4],
            ['name' => 'Kopi Sachet Box', 'buy' => 25000, 'sell' => 32000, 'stock' => 20, 'cat' => 2],
            ['name' => 'Roti Tawar', 'buy' => 12000, 'sell' => 15000, 'stock' => 5, 'cat' => 1], // Low stock
        ];

        foreach ($products as $index => $prod) {
            Product::create([
                'category_id' => $prod['cat'],
                'name' => $prod['name'],
                'sku' => 'SKU-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'barcode' => '899' . rand(1000000000, 9999999999),
                'buy_price' => $prod['buy'],
                'sell_price' => $prod['sell'],
                'stock' => $prod['stock'],
                'min_stock' => 10,
                'is_active' => true,
            ]);
        }
    }
}