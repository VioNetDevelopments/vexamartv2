<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code')->unique();
            $table->foreignId('user_id')->constrained(); // Kasir
            $table->foreignId('customer_id')->nullable()->constrained(); // Optional
            $table->integer('total_item')->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->enum('payment_method', ['cash', 'qris', 'debit', 'ewallet'])->default('cash');
            $table->enum('payment_status', ['paid', 'pending', 'failed'])->default('paid');
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->decimal('change_amount', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('invoice_code');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};