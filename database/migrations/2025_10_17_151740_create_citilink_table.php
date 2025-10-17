<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('citilink', function (Blueprint $table) {
            $table->id();
            $table->date('TANGGAL');
            $table->string('JAM');
            $table->decimal('TOP_UP', 15, 2)->nullable();
            $table->decimal('TRANSAKSI', 15, 2)->nullable();
            $table->decimal('INSENTIF', 15, 2)->nullable();
            $table->decimal('SALDO', 15, 2)->nullable();
            $table->string('KETERANGAN')->nullable();
            $table->string('USR')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('citilink');
    }
};

