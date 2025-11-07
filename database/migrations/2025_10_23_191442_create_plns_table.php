<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pln', function (Blueprint $table) {
            // Jika id di accounts adalah bigint
            $table->id(); // Auto-increment BIGINT primary key
            $table->dateTime('tgl')->nullable();
            $table->integer('id_pel')->nullable();
            $table->integer('harga_jual')->nullable();
            $table->integer('transaksi')->nullable();
            $table->string('bayar', 45)->nullable();
            $table->string('nama_piutang')->nullable();
            $table->integer('top_up')->nullable();
            $table->integer('insentif')->nullable();
            $table->integer('saldo')->nullable();
            $table->dateTime('tgl_reralisasi')->nullable();
            $table->timestamp('jam_realisasi')->nullable();
            
            $table->string('username', 45);
            $table->foreign('username')
                  ->references('username')
                  ->on('accounts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
                  
            $table->index('username', 'fk_pln_accounts1_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pln');
    }
};