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
        Schema::create('pembayaran_online', function (Blueprint $table) {
            $table->id(); // id INT
            $table->dateTime('tgl'); // tgj DATETIME
            $table->string('id_pel', 20); // tgj_pel DATE
            $table->foreignId('jenis_ppob_id')->constrained('jenis_ppob');
            $table->integer('nta'); // nta INT
            $table->integer('harga_jual'); // harga_juai INT
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran_online');
    }
};
