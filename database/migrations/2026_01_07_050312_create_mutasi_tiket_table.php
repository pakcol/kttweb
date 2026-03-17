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

            // ✅ FIX BUG 1: kolom string dideklarasikan DULU, baru foreign key
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

            // ✅ FIX BUG 2: jenis_bayar_id dibuat nullable
            // (subagent tidak memiliki jenis_bayar)
            $table->foreignId('jenis_bayar_id')
                  ->nullable()
                  ->constrained('jenis_bayar')
                  ->nullOnDelete();

            // Relasi ke bank (nullable)
            $table->foreignId('bank_id')
                  ->nullable()
                  ->constrained('bank')
                  ->nullOnDelete();

            $table->foreignId('piutang_id')
                  ->nullable()
                  ->constrained()
                  ->nullOnDelete();

            // ✅ FIX BUG 4: tambah kolom nama_piutang yang dipakai di controller
            $table->string('nama_piutang', 100)->nullable();

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi_tiket');
    }
};
