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
        Schema::create('subagents', function (Blueprint $table) {
            $table->id(); // id INT
            $table->dateTime('tgl_issued'); // tglistued DATETIME
            $table->string('name', 45); // name VARCHAR(45)
            $table->string('tiket_kode_booking', 10); // tiket_kodeBooling VARCHAR(10)
            $table->integer('saldo'); // saldo INT
            $table->timestamps();
            
            $table->foreign('tiket_kode_booking')->references('kode_booking')->on('tiket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subagents');
    }
};
