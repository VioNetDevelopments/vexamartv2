<?php
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

$owner = User::first();
$userId = $owner ? $owner->id : 1;

$days = [
    '2026-04-11' => ['transactions' => 15, 'base_amount' => 80000, 'variation' => 60000],
    '2026-04-12' => ['transactions' => 20, 'base_amount' => 120000, 'variation' => 80000],
    '2026-04-13' => ['transactions' => 27, 'base_amount' => 180000, 'variation' => 120000],
    '2026-04-14' => ['transactions' => 38, 'base_amount' => 230000, 'variation' => 150000],
    '2026-04-15' => ['transactions' => 50, 'base_amount' => 310000, 'variation' => 200000],
];

// Weighted to favor 'card' and 'bank' massively
$methods = [
    'card', 'card', 'card', 'card', 
    'bank', 'bank', 'bank', 
    'qris', 'qris', 
    'cash', 
    'debit', 
    'ewallet'
];

$count = 0;
foreach ($days as $dateStr => $data) {
    for ($i = 0; $i < $data['transactions']; $i++) {
        $amount = $data['base_amount'] + rand(0, $data['variation']);
        $totalItem = rand(1, 8);
        $method = $methods[array_rand($methods)];
        
        $date = Carbon::parse($dateStr)->addHours(rand(8, 21))->addMinutes(rand(0, 59))->addSeconds(rand(0, 59));
        
        Transaction::insert([
            'invoice_code' => 'VMS-' . strtoupper(Str::random(8)),
            'user_id' => $userId,
            'customer_id' => null,
            'total_item' => $totalItem,
            'subtotal' => $amount,
            'discount' => 0,
            'tax' => 0,
            'grand_total' => $amount,
            'payment_method' => $method,
            'paid_amount' => $amount,
            'change_amount' => 0,
            'payment_status' => 'paid',
            'created_at' => $date,
            'updated_at' => $date,
        ]);
        $count++;
    }
}
echo "$count Dummy transactions injected successfully!\n";
