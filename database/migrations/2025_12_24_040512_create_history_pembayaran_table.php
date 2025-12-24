<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('history_pembayaran', function (Blueprint $table) {
            $table->id();
            $table->string('tiket_kode_booking', 10);
            $table->foreignId('nota_id')->nullable()->constrained('nota');
            $table->foreignId('hutang_id')->nullable()->constrained('hutang');
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan', 200)->nullable();
            $table->string('tipe'); // 'pembayaran' atau 'pelunasan'
            $table->timestamps();
            
            $table->foreign('tiket_kode_booking')->references('kode_booking')->on('tiket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_pembayaran');
    }
};
