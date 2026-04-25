<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $providers = [
            // Banks
            ['name' => 'BCA', 'type' => 'bank', 'account_number' => '1234567890', 'account_name' => 'VEXAMART STORE', 'sort_order' => 1],
            ['name' => 'MANDIRI', 'type' => 'bank', 'account_number' => '0987654321', 'account_name' => 'VEXAMART STORE', 'sort_order' => 2],
            ['name' => 'BNI', 'type' => 'bank', 'account_number' => '1122334455', 'account_name' => 'VEXAMART STORE', 'sort_order' => 3],
            ['name' => 'BRI', 'type' => 'bank', 'account_number' => '5544332211', 'account_name' => 'VEXAMART STORE', 'sort_order' => 4],
            
            // E-Wallets
            ['name' => 'DANA', 'type' => 'ewallet', 'account_number' => '081234567890', 'account_name' => 'VEXAMART', 'sort_order' => 5],
            ['name' => 'OVO', 'type' => 'ewallet', 'account_number' => '081234567890', 'account_name' => 'VEXAMART', 'sort_order' => 6],
            ['name' => 'GOPAY', 'type' => 'ewallet', 'account_number' => '081234567890', 'account_name' => 'VEXAMART', 'sort_order' => 7],
            ['name' => 'SHOPEEPAY', 'type' => 'ewallet', 'account_number' => '081234567890', 'account_name' => 'VEXAMART', 'sort_order' => 8],
            ['name' => 'LINKAJA', 'type' => 'ewallet', 'account_number' => '081234567890', 'account_name' => 'VEXAMART', 'sort_order' => 9],
        ];

        foreach ($providers as $provider) {
            \App\Models\PaymentProvider::updateOrCreate(
                ['name' => $provider['name'], 'type' => $provider['type']],
                $provider
            );
        }
    }
}
