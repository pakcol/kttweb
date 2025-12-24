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
        Schema::create('jenis_bayar', function (Blueprint $table) {
            $table->id(); // id INT
            $table->string('jenis', 15); // jenis VARCHAR(15)
            $table->foreignId('bank_id')->nullable()->constrained('bank'); // bank_id INT
            $table->text('indicacs')->nullable(); // indicacs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_bayar');
    }
};
