<?php
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

$owner = User::first();
$userId = $owner ? $owner->id : 1;

$methods = ['cash', 'cash', 'qris', 'qris', 'qris', 'card', 'bank', 'debit', 'ewallet', 'ewallet'];

$count = 0;
// We start from 30 days ago to yesterday, growing the base amount slowly, with some random spikes
for ($i = 30; $i >= 0; $i--) {
    $dateStr = now()->subDays($i)->format('Y-m-d');
    
    // Simulate a gentle organic curve: lower in the past, rising towards today
    // with a base amount from 50k to 250k
    $progress = (30 - $i) / 30; // 0 to 1
    $baseAmount = 50000 + ($progress * 200000); 
    
    // Weekend spike (Saturday/Sunday)
    $isWeekend = now()->subDays($i)->isWeekend();
    if ($isWeekend) {
        $baseAmount *= 1.5;
    }
    
    // Randomize daily transactions from 5 to 25
    $dailyTxCount = rand(5, 25);
    
    for ($j = 0; $j < $dailyTxCount; $j++) {
        $amount = $baseAmount + rand(0, 100000);
        $totalItem = rand(1, 6);
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
echo "$count Mixed transactions over 30 days injected successfully!\n";
