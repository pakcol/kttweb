<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ===== MUTASI BANK =====
        Schema::table('mutasi_bank', function (Blueprint $table) {
            $table->decimal('debit', 15, 2)->default(0)->change();
            $table->decimal('kredit', 15, 2)->default(0)->change();
            $table->decimal('saldo', 15, 2)->default(0)->change();
        });

        // ===== TIKET =====
        Schema::table('tiket', function (Blueprint $table) {
            $table->decimal('nta', 15, 2)->default(0)->change();
            $table->decimal('komisi', 15, 2)->default(0)->change();
            $table->decimal('harga_jual', 15, 2)->default(0)->change();
            $table->decimal('diskon', 15, 2)->default(0)->change();
            $table->decimal('nilai_refund', 15, 2)->nullable()->change();
        });

        // ===== PPOB HISTORIES =====
        Schema::table('ppob_histories', function (Blueprint $table) {
            $table->decimal('nta', 15, 2)->default(0)->change();
            $table->decimal('harga_jual', 15, 2)->default(0)->change();
            $table->decimal('top_up', 15, 2)->default(0)->change();
            $table->decimal('komisi', 15, 2)->default(0)->change();
            $table->decimal('saldo', 15, 2)->default(0)->change();
        });

        // ===== TOPUP HISTORIES =====
        Schema::table('topup_histories', function (Blueprint $table) {
            $table->decimal('transaksi', 15, 2)->default(0)->change();
        });

        // ===== SUBAGENTS =====
        Schema::table('subagents', function (Blueprint $table) {
            $table->decimal('saldo', 15, 2)->default(0)->change();
        });

        // ===== SUBAGENT HISTORIES =====
        Schema::table('subagent_histories', function (Blueprint $table) {
            $table->decimal('transaksi', 15, 2)->default(0)->change();
        });

        // ===== BIAYA =====
        Schema::table('biaya', function (Blueprint $table) {
            $table->decimal('biaya', 15, 2)->default(0)->change();
        });
    }

    public function down(): void
    {
        // ===== MUTASI BANK =====
        Schema::table('mutasi_bank', function (Blueprint $table) {
            $table->bigInteger('debit')->default(0)->change();
            $table->bigInteger('kredit')->default(0)->change();
            $table->bigInteger('saldo')->default(0)->change();
        });

        // ===== TIKET =====
        Schema::table('tiket', function (Blueprint $table) {
            $table->integer('nta')->change();
            $table->integer('komisi')->change();
            $table->integer('harga_jual')->change();
            $table->integer('diskon')->default(0)->change();
            $table->integer('nilai_refund')->nullable()->change();
        });

        // ===== PPOB HISTORIES =====
        Schema::table('ppob_histories', function (Blueprint $table) {
            $table->integer('nta')->change();
            $table->integer('harga_jual')->change();
            $table->integer('top_up')->default(0)->change();
            $table->integer('komisi')->default(0)->change();
            $table->integer('saldo')->default(0)->change();
        });

        // ===== TOPUP HISTORIES =====
        Schema::table('topup_histories', function (Blueprint $table) {
            $table->integer('transaksi')->change();
        });

        // ===== SUBAGENTS =====
        Schema::table('subagents', function (Blueprint $table) {
            $table->integer('saldo')->change();
        });

        // ===== SUBAGENT HISTORIES =====
        Schema::table('subagent_histories', function (Blueprint $table) {
            $table->integer('transaksi')->change();
        });

        // ===== BIAYA =====
        Schema::table('biaya', function (Blueprint $table) {
            $table->integer('biaya')->change();
        });
    }
};
