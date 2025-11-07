<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket', function (Blueprint $table) {
            if (!Schema::hasColumn('ticket', 'jamRealisasi')) {
                $table->string('jamRealisasi', 10)->nullable()->after('tglRealisasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ticket', function (Blueprint $table) {
            $table->dropColumn(['jamRealisasi']);
        });
    }
};
