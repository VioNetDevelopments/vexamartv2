<?php

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// Clear old seeded data (keep real POS transactions)
// We'll add fresh data for the last 30 days with realistic organic patterns

$products = DB::table('products')->select('id', 'sell_price')->get();
if ($products->isEmpty()) {
    echo "No products found. Aborting.\n";
    return;
}

$paymentMethods = ['cash', 'qris', 'debit', 'ewallet', 'card', 'bank'];
$userId = DB::table('users')->where('role', 'owner')->orWhere('role', 'admin')->value('id') ?? 1;
$customerIds = DB::table('customers')->pluck('id')->toArray();
if (empty($customerIds)) $customerIds = [null];

$count = 0;
$now = Carbon::now();

for ($day = 30; $day >= 0; $day--) {
    $date = $now->copy()->subDays($day);
    $dayOfWeek = $date->dayOfWeek; // 0=Sunday

    // Realistic transaction volume: weekends busier
    $baseVolume = rand(8, 14);
    if ($dayOfWeek == 0 || $dayOfWeek == 6) {
        $baseVolume = rand(14, 22); // Weekend surge
    }
    if ($dayOfWeek == 5) {
        $baseVolume = rand(12, 18); // Friday boost
    }

    // Add some randomness for organic feel
    $txnCount = $baseVolume + rand(-2, 3);
    if ($txnCount < 4) $txnCount = 4;

    for ($t = 0; $t < $txnCount; $t++) {
        // Random time spread throughout operating hours (7am - 9pm)
        $hour = rand(7, 21);
        $minute = rand(0, 59);
        $second = rand(0, 59);
        $txnDate = $date->copy()->setTime($hour, $minute, $second);

        // Random 1-4 items per transaction
        $itemCount = rand(1, 4);
        $selectedProducts = $products->random(min($itemCount, $products->count()));

        $subtotal = 0;
        $totalItems = 0;
        $items = [];

        foreach ($selectedProducts as $product) {
            $qty = rand(1, 5);
            $price = (float) $product->sell_price;
            $itemSubtotal = $qty * $price;
            $subtotal += $itemSubtotal;
            $totalItems += $qty;

            $items[] = [
                'product_id' => $product->id,
                'qty' => $qty,
                'price' => $price,
                'discount' => 0,
                'subtotal' => $itemSubtotal,
                'note' => null,
                'created_at' => $txnDate,
                'updated_at' => $txnDate,
            ];
        }

        // Occasional small discount (15% chance)
        $discount = 0;
        if (rand(1, 100) <= 15) {
            $discount = round($subtotal * rand(5, 15) / 100, -2); // Round to nearest 100
        }

        $grandTotal = $subtotal - $discount;
        if ($grandTotal < 0) $grandTotal = $subtotal;

        $paymentMethod = $paymentMethods[array_rand($paymentMethods)];

        // Weighted: cash and qris more common
        $weights = ['cash' => 30, 'qris' => 28, 'debit' => 12, 'ewallet' => 15, 'card' => 8, 'bank' => 7];
        $rand = rand(1, 100);
        $cumulative = 0;
        foreach ($weights as $method => $weight) {
            $cumulative += $weight;
            if ($rand <= $cumulative) {
                $paymentMethod = $method;
                break;
            }
        }

        $paidAmount = $grandTotal;
        $changeAmount = 0;
        if ($paymentMethod === 'cash') {
            // Round up paid amount for cash
            $roundUp = [0, 5000, 10000, 20000, 50000];
            $extra = $roundUp[array_rand($roundUp)];
            $paidAmount = $grandTotal + $extra;
            $changeAmount = $paidAmount - $grandTotal;
        }

        $invoiceCode = 'INV-' . $txnDate->format('Ymd') . '-S' . str_pad($count + 1, 5, '0', STR_PAD_LEFT);

        $customerId = $customerIds[array_rand($customerIds)];

        $transactionId = DB::table('transactions')->insertGetId([
            'invoice_code' => $invoiceCode,
            'user_id' => $userId,
            'customer_id' => $customerId,
            'total_item' => $totalItems,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => 0,
            'grand_total' => $grandTotal,
            'payment_method' => $paymentMethod,
            'payment_provider' => null,
            'payment_status' => 'paid',
            'paid_amount' => $paidAmount,
            'change_amount' => $changeAmount,
            'notes' => null,
            'created_at' => $txnDate,
            'updated_at' => $txnDate,
        ]);

        foreach ($items as &$item) {
            $item['transaction_id'] = $transactionId;
        }
        DB::table('transaction_items')->insert($items);

        $count++;
    }
}

echo "{$count} organic transactions injected over 30 days!\n";
