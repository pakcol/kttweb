<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('insentif', function (Blueprint $table) {
            $table->id();
            $table->date('tgl');
            $table->bigInteger('jumlah');
            $table->unsignedBigInteger('jenis_tiket_id')->nullable();
            $table->unsignedBigInteger('jenis_ppob_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('jenis_tiket_id')
                ->references('id')
                ->on('jenis_tiket')
                ->nullOnDelete();

            $table->foreign('jenis_ppob_id')
                ->references('id')
                ->on('jenis_ppob')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('insentif');
    }
};
