<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subagent_histories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tgl_issued');

            // RELASI SUBAGENT
            $table->foreignId('subagent_id')
                ->constrained('subagents')
                ->cascadeOnDelete();

            // RELASI TIKET (PK = kode_booking STRING)
            $table->string('kode_booking');
            
            $table->integer('harga_beli');
            $table->integer('harga_jual')->nullable();
            $table->integer('saldo_awal');
            $table->integer('saldo_akhir');

            $table->timestamps();

            // FOREIGN KEY KE TABEL tiket
            $table->foreign('kode_booking')
                ->references('kode_booking')
                ->on('tiket')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subagent_histories');
    }
};
