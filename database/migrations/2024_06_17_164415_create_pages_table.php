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
         Schema::create('page', function (Blueprint $table) {
            $table->id();
            $table->string('store_view');
            $table->string('title');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('meta_data')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page');
    }
};
