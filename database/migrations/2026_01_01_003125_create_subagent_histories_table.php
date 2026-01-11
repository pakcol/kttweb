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
            $table->string('kode_booking') ->nullable();
            $table->enum('status', ['top_up', 'pesan_tiket', 'refunded'])->default('pesan_tiket');
            
            // NILAI TRANSAKSI (+ topup, - pesan tiket)
            $table->integer('transaksi');

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
