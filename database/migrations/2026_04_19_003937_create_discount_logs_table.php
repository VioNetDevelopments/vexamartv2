<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('discount_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('transaction_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('discount_percentage', 5, 2);
            $table->decimal('discount_amount', 15, 2);
            $table->text('reason')->nullable();
            $table->string('approved_by')->nullable(); // Supervisor approval
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_logs');
    }
};