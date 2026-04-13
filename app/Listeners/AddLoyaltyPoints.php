<?php

namespace App\Listeners;

use App\Events\TransactionCompleted;
use App\Models\Customer;

class AddLoyaltyPoints
{
    public function handle(TransactionCompleted $event)
    {
        $transaction = $event->transaction;
        
        // Calculate points (1 point per Rp 1000 spent)
        $points = floor($transaction->grand_total / 1000);
        
        // Update customer if exists
        if ($transaction->customer_id) {
            $customer = Customer::find($transaction->customer_id);
            
            if ($customer) {
                // Add points
                $customer->addPoints($points);
                
                // Update total spent
                $customer->total_spent += $transaction->grand_total;
                
                // Update last transaction
                $customer->last_transaction_at = $transaction->created_at;
                
                // Update membership tier
                $customer->updateMembership();
                
                $customer->save();
            }
        }
    }
}