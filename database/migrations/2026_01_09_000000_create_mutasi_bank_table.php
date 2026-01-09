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
        Schema::create('mutasi_bank', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bank_id')
                  ->constrained('bank')
                  ->cascadeOnDelete();

            $table->dateTime('tanggal');

            $table->bigInteger('debit')->default(0);     // uang MASUK ke bank
            $table->bigInteger('kredit')->default(0);    // uang KELUAR dari bank
            $table->bigInteger('saldo')->default(0);     // saldo bank setelah transaksi ini

            $table->string('keterangan', 255)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_bank');
    }
};

