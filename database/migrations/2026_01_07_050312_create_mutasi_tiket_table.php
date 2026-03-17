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

            // Foreign key ke tiket via string kode_booking
            $table->string('tiket_kode_booking', 10);
            $table->foreign('tiket_kode_booking')
                ->references('kode_booking')
                ->on('tiket')
                ->cascadeOnDelete();

            // Tanggal
            $table->datetime('tgl_issued');
            $table->datetime('tgl_bayar')->nullable();

            // Nominal
            $table->decimal('harga_bayar', 15, 2)->default(0);
            $table->decimal('insentif', 15, 2)->default(0);

            // jenis_bayar_id NULLABLE sejak awal — subagent tidak punya jenis_bayar
            $table->unsignedBigInteger('jenis_bayar_id')->nullable();
            $table->foreign('jenis_bayar_id')
                ->references('id')
                ->on('jenis_bayar')
                ->nullOnDelete();

            // bank (nullable)
            $table->unsignedBigInteger('bank_id')->nullable();
            $table->foreign('bank_id')
                ->references('id')
                ->on('bank')
                ->nullOnDelete();

            // Relasi ke tabel piutangs — TIDAK ada kolom nama_piutang
            $table->unsignedBigInteger('piutang_id')->nullable();
            $table->foreign('piutang_id')
                ->references('id')
                ->on('piutangs')
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
