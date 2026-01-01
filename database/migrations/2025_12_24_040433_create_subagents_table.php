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
            $table->string('nama', 45); // name VARCHAR(45)
            $table->integer('saldo'); // saldo INT
            $table->timestamps();
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
