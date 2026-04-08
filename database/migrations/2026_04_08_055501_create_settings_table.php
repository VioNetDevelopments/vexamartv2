<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            ['key' => 'company_name', 'value' => 'CV. ANUGERAH'],
            ['key' => 'company_address', 'value' => 'JL. RAYA WALIKUKUN NO. 9'],
            ['key' => 'company_city', 'value' => 'WIDODAREN NGAWI JAWA TIMUR'],
            ['key' => 'company_npwp', 'value' => '0025862640646000'],
            ['key' => 'store_name', 'value' => 'VEXAMART'],
            ['key' => 'store_address', 'value' => 'JL. BHAYANGKARA NO.60 KEL REJOSARI'],
            ['key' => 'store_city', 'value' => 'KEC KAWEDANAN, KAB MAGETAN, 63382'],
            ['key' => 'store_phone', 'value' => '0812-3456-7890'],
            ['key' => 'store_email', 'value' => 'info@vexamart.com'],
            ['key' => 'receipt_footer', 'value' => 'TERIMA KASIH'],
            ['key' => 'tax_rate', 'value' => '0'],
            ['key' => 'currency', 'value' => 'Rp'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};