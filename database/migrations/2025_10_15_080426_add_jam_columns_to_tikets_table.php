<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tikets', function (Blueprint $table) {
            if (!Schema::hasColumn('tikets', 'jam_realisasi')) {
                $table->time('jam_realisasi')->nullable()->after('tgl_realisasi');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tikets', function (Blueprint $table) {
            $table->dropColumn(['jam_realisasi']);
        });
    }
};
