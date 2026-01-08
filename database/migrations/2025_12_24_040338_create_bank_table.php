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
        Schema::create('bank', function (Blueprint $table) {
            $table->id(); // id INT
            $table->string('name', 45); // name VARCHAR(45)
            $table->decimal('saldo', 15, 2); // saldo VARCHAR(45) as decimal
            $table->text('indicacs')->nullable(); // indicacs (as text)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank');
    }
};
