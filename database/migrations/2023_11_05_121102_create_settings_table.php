<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by');
            $table->string('domain')->nullable();
            $table->text('copyright_text')->nullable();
            $table->text('footer_text')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('logo_text')->nullable();
            $table->string('logo')->nullable();
            $table->string('footer_logo');
            $table->string('web_icon')->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('server');
            $table->integer('port');
            $table->string('encryption');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
