<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Nonaktifkan foreign key check sementara agar drop bisa dilakukan
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Drop subagent_histories dulu (dia yang punya FK ke tiket & subagents)
        Schema::dropIfExists('subagent_histories');

        // Drop topup_histories dulu jika masih punya FK ke subagents
        // (kolom subagent_id sudah dihapus dari migration baru,
        //  tapi jika DB lama masih ada kolomnya, drop FK dulu)
        if (Schema::hasColumn('topup_histories', 'subagent_id')) {
            Schema::table('topup_histories', function ($table) {
                $table->dropForeign(['subagent_id']);
                $table->dropColumn('subagent_id');
            });
        }

        // Drop tabel subagents
        Schema::dropIfExists('subagents');

        // Aktifkan kembali foreign key check
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // Tidak perlu recreate — subagent sudah dihapus permanen dari sistem
    }
};
