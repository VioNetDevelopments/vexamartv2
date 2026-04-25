<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('flash_sales', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->integer('discount_percentage');
            $table->integer('max_quantity')->default(100);
            $table->integer('sold_quantity')->default(0);
            $table->datetime('starts_at');
            $table->datetime('ends_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};