<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@vexamart.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Kasir 1',
            'email' => 'kasir@vexamart.com',
            'password' => Hash::make('password'),
            'role' => 'cashier',
            'is_active' => true,
        ]);
    }
}