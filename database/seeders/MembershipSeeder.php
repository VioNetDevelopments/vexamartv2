<?php

namespace Database\Seeders;

use App\Models\Membership;
use Illuminate\Database\Seeder;

class MembershipSeeder extends Seeder
{
    public function run(): void
    {
        Membership::create([
            'name' => 'Free',
            'price' => 0,
            'duration_days' => 30,
            'benefits' => ['Akses belanja', 'Loyalty points 1x'],
            'discount_percentage' => 0,
            'loyalty_points_multiplier' => 1,
            'is_active' => true,
        ]);

        Membership::create([
            'name' => 'Silver',
            'price' => 50000,
            'duration_days' => 30,
            'benefits' => ['Diskon 5%', 'Loyalty points 2x', 'Free ongkir 3x'],
            'discount_percentage' => 5,
            'loyalty_points_multiplier' => 2,
            'is_active' => true,
        ]);

        Membership::create([
            'name' => 'Gold',
            'price' => 100000,
            'duration_days' => 30,
            'benefits' => ['Diskon 10%', 'Loyalty points 3x', 'Free ongkir unlimited', 'Priority support'],
            'discount_percentage' => 10,
            'loyalty_points_multiplier' => 3,
            'is_active' => true,
        ]);

        Membership::create([
            'name' => 'Platinum',
            'price' => 200000,
            'duration_days' => 30,
            'benefits' => ['Diskon 15%', 'Loyalty points 5x', 'Free ongkir unlimited', '24/7 VIP support', 'Early access flash sale'],
            'discount_percentage' => 15,
            'loyalty_points_multiplier' => 5,
            'is_active' => true,
        ]);
    }
}