<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'loyalty_points')) {
                $table->integer('loyalty_points')->default(0)->after('membership');
            }
            if (!Schema::hasColumn('customers', 'total_spent')) {
                $table->decimal('total_spent', 15, 2)->default(0)->after('loyalty_points');
            }
            if (!Schema::hasColumn('customers', 'last_transaction_at')) {
                $table->timestamp('last_transaction_at')->nullable()->after('total_spent');
            }
        });
    }

    public function down(): void
    {
        // Don't drop columns in down() to avoid data loss
    }
};