<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add tagline if not exists
        DB::table('settings')->insertOrIgnore([
            ['key' => 'store_tagline', 'value' => 'Solusi Belanja Modern'],
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'store_tagline')->delete();
    }
};