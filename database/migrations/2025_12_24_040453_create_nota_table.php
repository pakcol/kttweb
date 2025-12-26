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
        Schema::create('nota', function (Blueprint $table) {
            $table->id(); // id INT
            $table->dateTime('tgl_issued'); // tgj_lssued DATETIME
            $table->dateTime('tgl_bayar')->nullable(); // tgj_bayar DATETIME
            $table->integer('harga_bayar'); // harga_bayar INT
            $table->foreignId('jenis_bayar_id')->constrained('jenis_bayar'); // jenis_bayar_id INT
            $table->foreignId('bank_id')->nullable()->constrained('bank');
            $table->foreignId('pembayaran_online_id')->nullable()->constrained('pembayaran_online'); // pembayaranOnline_id INT
            $table->string('tiket_kode_booking', 10); // tiket_kodeBooling VARCHAR(10)
            $table->timestamps();
            
            $table->foreign('tiket_kode_booking')->references('kode_booking')->on('tiket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota');
    }
};
