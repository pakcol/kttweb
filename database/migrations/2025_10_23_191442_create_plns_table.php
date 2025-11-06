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
            $table->string('nama_piutang', 45)->nullable();
            $table->integer('top_up')->nullable();
            $table->integer('insentif')->nullable();
            $table->integer('saldo')->nullable();
            $table->dateTime('tgl_realisasi')->nullable();
            $table->timestamp('jam_realisasi')->nullable();
            
            // Sesuaikan dengan tipe data id di accounts
            $table->unsignedBigInteger('account_id');
            
            // Foreign key constraint
            $table->foreign('account_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('no action')
                  ->onUpdate('no action');
                  
            $table->index('account_id', 'fk_pln_accounts1_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pln');
    }
};