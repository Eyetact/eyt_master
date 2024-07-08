<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_view', function (Blueprint $table) {
            $table->id();
            $table->string('region');
            $table->string('language');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('meta_data')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_view');
    }
};
