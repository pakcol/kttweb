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
        Schema::create('ppob_histories', function (Blueprint $table) {
            $table->id(); // id INT
            $table->dateTime('tgl'); // tgj DATETIME
            $table->string('id_pel', 20); // tgj_pel DATE
            $table->foreignId('jenis_ppob_id')->constrained('jenis_ppob');
            $table->integer('nta'); // nta INT
            $table->integer('harga_jual'); // harga_juai INT
            $table->decimal('insentif', 15, 2)->nullable();
            $table->integer('top_up')->default(0);
            $table->integer('komisi')->default(0);
            $table->integer('saldo')->default(0);
            $table->string('nama_piutang', 100)->nullable();

            // Relasi ke jenis bayar (nullable)
            $table->foreignId('jenis_bayar_id')
                  ->constrained('jenis_bayar');

            // Relasi ke bank (nullable)
            $table->foreignId('bank_id')
                  ->nullable()
                  ->constrained('bank')
                  ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppob_histories');
    }
};
