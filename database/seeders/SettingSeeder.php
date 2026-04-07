<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create(['key' => 'store_name', 'value' => 'VexaMart Official']);
        Setting::create(['key' => 'store_address', 'value' => 'Jl. Teknologi No. 12, Jakarta']);
        Setting::create(['key' => 'tax_rate', 'value' => '0']); // 0% default
        Setting::create(['key' => 'currency', 'value' => 'Rp']);
    }
}