<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tglIssued');
            $table->string('jam', 10);
            $table->string('kodeBooking', 10);
            $table->string('airlines', 20);
            $table->string('nama', 100);
            $table->string('rute1', 45);
            $table->dateTime('tglFlight1');
            $table->string('rute2', 45)->nullable();
            $table->dateTime('tglFlight2')->nullable();
            $table->integer('harga');
            $table->integer('nta');
            $table->integer('diskon');
            $table->integer('komisi');
            $table->string('pembayaran', 10);
            $table->string('namaPiutang')->nullable();
            $table->dateTime('tglRealisasi')->nullable();
            $table->string('jamRealisasi', 10)->nullable(); 
            $table->integer('nilaiRefund')->default(0);
            $table->string('keterangan', 45)->nullable();
            $table->string('username', 45);
            $table->foreign('username')->references('username')->on('accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ticket');
    }
};
