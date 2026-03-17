<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi_tiket', function (Blueprint $table) {
            $table->id();

            $table->string('tiket_kode_booking', 10);
            $table->foreign('tiket_kode_booking')
                ->references('kode_booking')
                ->on('tiket')
                ->cascadeOnDelete();

            $table->datetime('tgl_issued');
            $table->datetime('tgl_bayar')->nullable();

            $table->decimal('harga_bayar', 15, 2)->default(0);
            $table->decimal('insentif', 15, 2)->default(0);

            $table->foreignId('jenis_bayar_id')
                  ->nullable()
                  ->constrained('jenis_bayar')
                  ->nullOnDelete();

            $table->foreignId('bank_id')
                  ->nullable()
                  ->constrained('bank')
                  ->nullOnDelete();

            // Relasi ke tabel piutangs — TIDAK ada kolom nama_piutang
            $table->foreignId('piutang_id')
                  ->nullable()
                  ->constrained('piutangs')
                  ->nullOnDelete();

            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_tiket');
    }
};
