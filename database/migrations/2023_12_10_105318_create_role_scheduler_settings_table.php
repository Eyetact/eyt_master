<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleSchedulerSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_scheduler_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->integer('module_id');
            $table->integer('scheduler_no');
            $table->string('type');
            $table->boolean('status')->default(false);
            $table->dateTime('access_action_date_time');
            $table->string('model_access_action_permission');
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
        Schema::dropIfExists('role_scheduler_settings');
    }
}
