<?php

namespace App\Listeners;

use App\Models\Customer;
use Illuminate\Auth\Events\Registered;

class CreateCustomerFromUser
{
    public function handle(Registered $event)
    {
        $user = $event->user;
        
        // ✅ FIXED: Use firstOrCreate to prevent duplicates
        Customer::firstOrCreate(
            ['email' => $user->email], // Match by email
            [
                'name' => $user->name,
                'phone' => null,
                'address' => null,
                'membership' => 'regular',
                'loyalty_points' => 0,
                'total_spent' => 0,
                'last_transaction_at' => null,
                'is_active' => 1,
            ]
        );
    }
}