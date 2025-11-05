<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pln_piutang', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->time('jam')->nullable();
            $table->string('id_pel')->nullable();
            $table->decimal('harga_jual', 15, 2)->nullable();
            $table->string('transaksi')->nullable();
            $table->decimal('bayar', 15, 2)->nullable();
            $table->string('nama_piutang')->nullable();
            $table->decimal('top_up', 15, 2)->nullable();
            $table->decimal('insentif', 15, 2)->nullable();
            $table->decimal('saldo', 15, 2)->nullable();
            $table->string('usr')->nullable();
            $table->date('tgl_realisasi')->nullable();
            $table->time('jam_realisasi')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pln_piutang');
    }
};
