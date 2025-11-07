<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal');
            $table->timestamp('jam')->nullable();
            $table->decimal('TTL_PENJUALAN', 15, 2)->nullable();
            $table->decimal('TU_EVI', 15, 2)->nullable();
            $table->decimal('PIUTANG', 15, 2)->nullable();
            $table->decimal('REFUND', 15, 2)->nullable();
            $table->decimal('STR_BCA', 15, 2)->nullable();
            $table->decimal('STR_BNI', 15, 2)->nullable();
            $table->decimal('STR_MDR', 15, 2)->nullable();
            $table->decimal('STR_BRI', 15, 2)->nullable();
            $table->decimal('TRF_BCA', 15, 2)->nullable();
            $table->decimal('TRF_BNI', 15, 2)->nullable();
            $table->decimal('TRF_MDR', 15, 2)->nullable();
            $table->decimal('TRF_BRI', 15, 2)->nullable();
            $table->decimal('TRF_BTN', 15, 2)->nullable();
            $table->decimal('BIAYA', 15, 2)->nullable();
            $table->decimal('CASH_FLOW', 15, 2)->nullable();
            $table->decimal('SOEVI', 15, 2)->nullable();
            $table->decimal('SOCITILINK', 15, 2)->nullable();
            $table->decimal('SOGARUDA', 15, 2)->nullable();
            $table->decimal('SOLION', 15, 2)->nullable();
            $table->decimal('SOPELNI', 15, 2)->nullable();
            $table->decimal('SODLU', 15, 2)->nullable();
            $table->decimal('SOQGCORNER', 15, 2)->nullable();
            $table->decimal('SOSRIWIJAYA', 15, 2)->nullable();
            $table->decimal('SOTRANSNUSA', 15, 2)->nullable();
            $table->decimal('SOAIRASIA', 15, 2)->nullable();
            $table->decimal('TUCITILINK', 15, 2)->nullable();
            $table->decimal('TUGARUDA', 15, 2)->nullable();
            $table->decimal('TULION', 15, 2)->nullable();
            $table->decimal('TUPELNI', 15, 2)->nullable();
            $table->decimal('TUDLU', 15, 2)->nullable();
            $table->decimal('TUQGCORNER', 15, 2)->nullable();
            $table->decimal('TUSRIWIJAYA', 15, 2)->nullable();
            $table->decimal('TUTRANSNUSA', 15, 2)->nullable();
            $table->decimal('TUAIRASIA', 15, 2)->nullable();
            $table->decimal('PLN', 15, 2)->nullable();
            $table->decimal('SALDOPLN', 15, 2)->nullable();
            $table->string('username', 45)->nullable();
            $table->foreign('username')->references('username')->on('accounts')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('penjualan');
    }
};
