<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plns', function (Blueprint $table) {
            $table->id(); 
            $table->string('nama'); 
            $table->string('nomor_meteran'); 
            $table->string('daya'); 
            $table->integer('jumlah_tagihan'); 
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('plns');
    }
};
