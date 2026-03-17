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
            $table->datetime('tgl_issued');

            // nominal (+topup / +refund)
            $table->integer('transaksi');

            // ================= RELASI =================
            // target jenis tiket (nullable)
            $table->foreignId('jenis_tiket_id')
                ->nullable()
                ->constrained('jenis_tiket')
                ->nullOnDelete();

            // target subagent (nullable)
            $table->foreignId('subagent_id')
                ->nullable()
                ->constrained('subagents')
                ->nullOnDelete();

            // ✅ FIX BUG 3: jenis_bayar_id dibuat nullable
            // (mode refund tiket tidak selalu memiliki jenis_bayar)
            $table->foreignId('jenis_bayar_id')
                ->nullable()
                ->constrained('jenis_bayar')
                ->nullOnDelete();

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
