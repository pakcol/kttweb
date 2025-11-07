<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('biaya', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tgl');
            $table->string('jam', 10);
            $table->integer('biaya');
            $table->string('pembayaran');
            $table->string('keterangan', 20);
            $table->string('username', 45);
            $table->foreign('username')->references('username')->on('accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biaya');
    }
};

