<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tikets', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_issued');
            $table->timestamp('jam_input')->useCurrent();
            $table->string('kode_booking')->unique();
            $table->string('airlines');
            $table->string('nama');
            $table->string('rute1');
            $table->date('tgl_flight1');
            $table->string('rute2')->nullable();
            $table->date('tgl_flight2')->nullable();
            $table->decimal('harga', 15, 2);
            $table->decimal('nta', 15, 2);
            $table->decimal('diskon', 15, 2);
            $table->decimal('komisi', 15, 2);
            $table->string('pembayaran');
            $table->string('nama_piutang')->nullable();
            $table->date('tgl_realisasi')->nullable();
            $table->time('jam_realisasi')->nullable(); 
            $table->decimal('nilai_refund', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('usr');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tikets');
    }
};
