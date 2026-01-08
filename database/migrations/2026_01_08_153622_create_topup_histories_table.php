<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('topup_histories', function (Blueprint $table) {
            $table->id();

            // tanggal transaksi
            $table->date('tgl_issued');

            // nominal (+topup / +refund)
            $table->integer('transaksi');

            // ================= RELASI =================
            // target tiket (nullable)
            $table->foreignId('jenis_tiket_id')
                ->nullable()
                ->constrained('jenis_tiket')
                ->nullOnDelete();

            // target subagent (nullable)
            $table->foreignId('subagent_id')
                ->nullable()
                ->constrained('subagents')
                ->nullOnDelete();

            // jenis pembayaran (WAJIB)
            $table->foreignId('jenis_bayar_id')
                ->constrained('jenis_bayar');

            // bank (hanya jika BANK)
            $table->foreignId('bank_id')
                ->nullable()
                ->constrained('bank')
                ->nullOnDelete();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('topup_histories');
    }
};
