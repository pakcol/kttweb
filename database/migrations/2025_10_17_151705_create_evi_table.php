<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evi', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tgl');
            $table->timestamp('jam')->nullable();
            $table->string('kodeBooking', 45);
            $table->string('airlines', 45);
            $table->string('nama', 45);
            $table->string('rute1', 45)->nullable();
            $table->dateTime('tglFlight1')->nullable();
            $table->string('rute2', 45)->nullable();
            $table->dateTime('tglFlight2')->nullable();
            $table->integer('harga')->nullable();
            $table->integer('nta')->nullable();
            $table->integer('topup')->nullable();
            $table->integer('saldo')->nullable();
            $table->string('keterangan', 300)->nullable();
            $table->string('username', 45);
            $table->foreign('username')->references('username')->on('accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('evi');
    }
};

