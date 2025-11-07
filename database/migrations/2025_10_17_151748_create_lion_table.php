<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lion', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tgl');
            $table->string('jam', 10);
            $table->integer('top_up')->nullable();
            $table->integer('transaksi')->nullable();
            $table->integer('insentif')->nullable();
            $table->integer('saldo')->nullable();
            $table->string('keterangan', 30)->nullable();
            $table->string('username', 45);
            $table->foreign('username')->references('username')->on('accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('lion');
    }
};

