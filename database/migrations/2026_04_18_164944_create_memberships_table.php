<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Silver, Gold, Platinum
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('duration_days')->default(30);
            $table->json('benefits')->nullable();
            $table->integer('discount_percentage')->default(0);
            $table->integer('loyalty_points_multiplier')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memberships');
    }
};