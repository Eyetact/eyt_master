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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('store_view');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('web_image')->nullable();
            $table->string('mobile_image')->nullable();
            $table->string('primary_button_title')->nullable();
            $table->string('primary_button_url')->nullable();
            $table->string('secondary_button_title')->nullable();
            $table->string('secondary_button_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
