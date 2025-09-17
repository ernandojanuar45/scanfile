<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendersTable extends Migration
{
    public function up()
    {
        Schema::create('senders', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('alamat')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('senders');
    }
}
