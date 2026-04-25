<?php

namespace App\Listeners;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class CreateCustomerFromUser
{
    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        /** @var User $user */
        $user = $event->user;
        
        // Use updateOrCreate to handle existing customers (e.g., from POS)
        Customer::updateOrCreate(
            ['email' => $user->email],
            [
                'user_id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone ?? null,
                'address' => $user->address ?? null,
                'membership' => 'regular',
                'loyalty_points' => 0,
                'total_spent' => 0,
                'last_transaction_at' => null,
                'is_active' => 1,
            ]
        );
    }
}