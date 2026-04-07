<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qris_payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_code')->unique();
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->text('qr_data')->nullable();
            $table->string('payment_gateway_ref')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();

            $table->index('invoice_code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qris_payments');
    }
};