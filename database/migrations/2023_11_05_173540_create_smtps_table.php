<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmtpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smtps', function (Blueprint $table) {
            $table->id();
            $table->string('mailer_id');
            $table->string('transport_type');
            $table->string('mail_server')->nullable();
            $table->string('email');
            $table->string('password');
            $table->string('port');
            $table->string('encryption_mode');
            $table->string('imap_mail_server');
            $table->integer('imap_port');
            $table->string('imap_encryption_mode');
            $table->string('authentication_mode');
            $table->string('sender_address')->nullable();
            $table->string('delivery_address')->nullable();
            $table->unsignedBigInteger('created_by');
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
        Schema::dropIfExists('smtps');
    }
}
