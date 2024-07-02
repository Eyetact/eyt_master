<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttr extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['string', 'integer', 'text', 'bigInteger', 'boolean', 'char', 'date', 'time', 'year', 'dateTime', 'decimal', 'double', 'enum', 'float', 'foreignId', 'tinyInteger', 'mediumInteger', 'tinyText', 'mediumText', 'longText', 'multi']);
            $table->integer('min_length')->nullable();
            $table->integer('max_length')->nullable();
            $table->integer('steps')->nullable();
            $table->string('input');
            $table->string('required')->default('yes');
            $table->string('default_value')->nullable();
            $table->text('select_option')->nullable(); // for enum;
            $table->string('constrain')->nullable(); // for relation;
            $table->string('on_update_foreign')->nullable(); // for relation;
            $table->string('on_delete_foreign')->nullable(); // for relation;
            $table->boolean('is_enable')->default(1)->comment('0: disable, 1: enable');
            $table->integer('max_size')->nullable();
            $table->string('file_type')->nullable();


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
        Schema::dropIfExists('attributes');
    }
}
