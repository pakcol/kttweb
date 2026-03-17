<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Migration ini sudah tidak diperlukan.
 * Semua struktur sudah benar di migration awal (fresh install).
 * File ini dikosongkan agar tidak konflik saat php artisan migrate:fresh.
 */
return new class extends Migration
{
    public function up(): void
    {
        // no-op
    }

    public function down(): void
    {
        // no-op
    }
};
