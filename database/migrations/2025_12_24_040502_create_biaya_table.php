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
        Schema::create('biaya', function (Blueprint $table) {
            $table->id(); // id INT
            $table->dateTime('tgl'); // tgj DATETIME
            $table->integer('biaya'); // bisya INT
   
            // KATEGORI BIAYA
            $table->enum('kategori', ['top_up', 'setoran', 'lainnya'])
                ->default('lainnya');
                
            // Relasi ke jenis tiket (airlines)
            $table->foreignId('id_jenis_tiket')
                  ->nullable()
                  ->constrained('jenis_tiket')
                  ->cascadeOnUpdate()
                  ->nullOnDelete();

            $table->foreignId('jenis_bayar_id')->constrained('jenis_bayar'); // jenis_bayar_id INT
            $table->foreignId('bank_id')->nullable()->constrained('bank'); // bank_id INT
            $table->string('keterangan', 200)->nullable(); // keterangan VARCHAR(200)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('biaya');
    }
};
