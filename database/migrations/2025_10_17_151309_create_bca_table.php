<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bca', function (Blueprint $table) {
            $table->id();
            $table->date('TANGGAL');
            $table->string('JAM');
            $table->string('KETERANGAN')->nullable();
            $table->decimal('CREDIT', 15, 2)->nullable();
            $table->decimal('DEBIT', 15, 2)->nullable();
            $table->decimal('SALDO', 15, 2)->nullable();
            $table->string('USR')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('bca');
    }
};

