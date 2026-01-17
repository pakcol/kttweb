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

            // FOREIGN KEY KE TABEL tiket
            $table->foreign('tiket_kode_booking')
                ->references('kode_booking')
                ->on('tiket')
                ->cascadeOnDelete();
            $table->string('tiket_kode_booking', 10);
            // Tanggal
            $table->datetime('tgl_issued');
            $table->datetime('tgl_bayar')->nullable();

            // Nominal
            $table->decimal('harga_bayar', 15, 2)->default(0);
            $table->decimal('insentif', 15, 2)->default(0);

            // Relasi ke jenis bayar (nullable)
            $table->foreignId('jenis_bayar_id')
                  ->constrained('jenis_bayar');

            // Relasi ke bank (nullable)
            $table->foreignId('bank_id')
                  ->nullable()
                  ->constrained('bank')
                  ->nullOnDelete();
            $table->foreignId('piutang_id')->nullable()->constrained()->nullOnDelete();

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
