<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuratsTable extends Migration
{
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_kop')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('perihal')->nullable();
            $table->string('nama_penerima')->nullable();
            $table->string('nomor_pendaftaran')->nullable();
            $table->string('program_studi')->nullable();
            $table->string('nama')->nullable();
            $table->string('jalur_pendaftaran')->nullable();
            $table->string('email')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('no_wa')->nullable();
            $table->string('bagian')->nullable();
            $table->string('link_pembayaran')->nullable();
            $table->string('batas')->nullable(); // bisa diubah menjadi date jika memang tanggal
            $table->decimal('biaya', 15, 2)->nullable();
            $table->string('nomor_rekening')->nullable();
            $table->string('bank')->nullable();

            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('letters');
    }
}
