<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cashier_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->datetime('started_at');
            $table->datetime('ended_at')->nullable();
            $table->decimal('starting_cash', 15, 2)->default(0); // Modal awal
            $table->decimal('expected_cash', 15, 2)->default(0);
            $table->decimal('actual_cash', 15, 2)->default(0);
            $table->decimal('cash_shortage', 15, 2)->default(0); // Kekurangan (negative) / kelebihan (positive)
            $table->decimal('cash_deposit', 15, 2)->default(0); // Setor tunai
            $table->text('closing_notes')->nullable();
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cashier_shifts');
    }
};