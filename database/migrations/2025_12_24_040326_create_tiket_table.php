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
        Schema::create('tiket', function (Blueprint $table) {
            $table->string('kode_booking', 10)->primary(); // kodeBooling VARCHAR(10) as primary key
            $table->dateTime('tgl_issued'); // tglistued DATETIME
            $table->string('name', 100); // name VARCHAR(100)
            $table->integer('nta'); // nta INT
            $table->integer('komisi'); // komisi INT
            $table->integer('harga_jual'); // harga_jual INT
            $table->integer('diskon')->default(0);
            $table->string('rute', 45); // rute VARCHAR(45)
            $table->dateTime('tgl_flight'); // tgl_flight DATETIME
            $table->string('rute2', 45)->nullable(); // rute2 VARCHAR(45)
            $table->dateTime('tgl_flight2')->nullable(); // tgl_flight2 DATETIME
            $table->enum('status', ['issued', 'canceled', 'refunded']); // status ENUM
            $table->foreignId('jenis_tiket_id')->constrained('jenis_tiket'); // jenis_tiket_id INT
            $table->integer('nilai_refund')->nullable(); // nilai_refund INT
            $table->dateTime('tgl_realisasi')->nullable(); // biaya_refund INT
            $table->string('keterangan', 200)->nullable(); // keterangan VARCHAR(200)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiket');
    }
};
