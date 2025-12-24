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
        Schema::create('jenis_tiket', function (Blueprint $table) {
            $table->id(); // id INT
            $table->string('name_jenis', 45); // name_jenis VARCHAR(45)
            $table->integer('saldo'); // saldo INT
            $table->string('keterangan', 200); // keterangan VARCHAR(200)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_tiket');
    }
};
