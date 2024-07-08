<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('testmodules');
        Schema::create('testmodules', function (Blueprint $table) {
            $table->id();
            
            $table->integer('user_id')->nullable();
            $table->integer('sub_id')->nullable();
            $table->integer('data_id')->nullable();
            if (!Schema::hasColumn('testmodules', 'customer_group_id')){
                $table->integer('customer_group_id')->nullable();
            }
            if (!Schema::hasColumn('testmodules', 'customer_id')){
                $table->integer('customer_id')->nullable();
            }

            if (!Schema::hasColumn('testmodules', 'assign_id')){
                $table->integer('assign_id')->nullable();
            }

              if (!Schema::hasColumn('testmodules', 'global')){
                $table->boolean('global')->default(0);
            }
               if (!Schema::hasColumn('testmodules', 'status')){
                $table->text('status')->nullable()->default('inactive');
            }

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
        Schema::dropIfExists('testmodules');
    }
};
