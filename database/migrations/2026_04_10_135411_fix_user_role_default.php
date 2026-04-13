<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update default for future users
        DB::statement("ALTER TABLE users MODIFY role ENUM('cashier', 'admin', 'owner') DEFAULT 'cashier'");
        
        // Optionally update existing users with 'owner' role to 'cashier' (be careful!)
        // DB::table('users')->where('role', 'owner')->update(['role' => 'cashier']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('cashier', 'admin', 'owner') DEFAULT 'owner'");
    }
};