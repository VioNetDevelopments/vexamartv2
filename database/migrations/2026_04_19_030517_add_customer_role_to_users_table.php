<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL requires ALTER TABLE to change ENUM values
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('owner','admin','cashier','customer') NOT NULL DEFAULT 'cashier'");
    }

    public function down(): void
    {
        // Revert pending — first set any 'customer' rows back to 'cashier' to avoid data truncation
        DB::statement("UPDATE `users` SET `role` = 'cashier' WHERE `role` = 'customer'");
        DB::statement("ALTER TABLE `users` MODIFY `role` ENUM('owner','admin','cashier') NOT NULL DEFAULT 'cashier'");
    }
};
