<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // Who did it
            $table->enum('type', ['in', 'out', 'adjustment', 'sale']);
            $table->integer('qty'); // Positive for in, negative for out logic handled in app
            $table->string('reason')->nullable(); // e.g., "Restock", "Damaged", "Sale #INV001"
            $table->integer('stock_before');
            $table->integer('stock_after');
            $table->timestamps();
            
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};