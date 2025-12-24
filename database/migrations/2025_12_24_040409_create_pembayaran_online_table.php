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
        Schema::create('pembayaran_online', function (Blueprint $table) {
            $table->id(); // id INT
            $table->dateTime('tgl'); // tgj DATETIME
            $table->date('tgl_pel')->nullable(); // tgj_pel DATE
            $table->integer('nta'); // nta INT
            $table->integer('harga_jual'); // harga_juai INT
            $table->integer('saldo'); // saldo INT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_online');
    }
};
