<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // MUTASI BANK (buku bank utama)
        Schema::table('mutasi_bank', function (Blueprint $table) {
            $table->decimal('saldo_awal', 15, 2)->change();
            $table->decimal('debit', 15, 2)->change();
            $table->decimal('kredit', 15, 2)->change();
            $table->decimal('saldo_akhir', 15, 2)->change();
        });

        // TIKET
        Schema::table('tiket', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->change();
            $table->decimal('biaya_operasional', 15, 2)->change();
            $table->decimal('pajak', 15, 2)->change();
            $table->decimal('materai', 15, 2)->change();
            $table->decimal('total', 15, 2)->change();
        });

        // PPOB HISTORIES
        Schema::table('ppob_histories', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->change();
            $table->decimal('biaya', 15, 2)->change();
            $table->decimal('pajak', 15, 2)->change();
        });

        // SUBAGENT & TOPUP
        Schema::table('subagents', function (Blueprint $table) {
            $table->decimal('saldo', 15, 2)->change();
        });
        Schema::table('topup_histories', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->change();
        });
        Schema::table('subagent_histories', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->change();
        });

        // PIUTANG & BIAYA
        Schema::table('piutang', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->change();
        });
        Schema::table('biaya', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->change();
        });

        // MUTASI TIKET
        Schema::table('mutasi_tiket', function (Blueprint $table) {
            $table->decimal('nominal', 15, 2)->change();
        });
    }

    public function down()
    {
        // Rollback - ubah kembali ke integer
        Schema::table('mutasi_bank', function (Blueprint $table) {
            $table->unsignedBigInteger('saldo_awal')->change();
            $table->unsignedBigInteger('debit')->change();
            $table->unsignedBigInteger('kredit')->change();
            $table->unsignedBigInteger('saldo_akhir')->change();
        });
        // ... (sama untuk tabel lain, ganti ke unsignedBigInteger)
    }
};