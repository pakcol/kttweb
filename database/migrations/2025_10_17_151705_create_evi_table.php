<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evi', function (Blueprint $table) {
            $table->id();
            $table->date('TGL_ISSUED');
            $table->string('JAM');
            $table->string('KODEBOKING');
            $table->string('AIRLINES');
            $table->string('NAMA');
            $table->string('RUTE1')->nullable();
            $table->date('TGL_FLIGHT1')->nullable();
            $table->string('RUTE2')->nullable();
            $table->date('TGL_FLIGHT2')->nullable();
            $table->decimal('HARGA', 15, 2)->nullable();
            $table->decimal('NTA', 15, 2)->nullable();
            $table->decimal('TOP_UP', 15, 2)->nullable();
            $table->decimal('SALDO', 15, 2)->nullable();
            $table->string('KETERANGAN')->nullable();
            $table->string('USR')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('evi');
    }
};

