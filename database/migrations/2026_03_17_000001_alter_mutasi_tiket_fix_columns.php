<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration ini memperbaiki kolom pada tabel mutasi_tiket dan topup_histories
 * untuk database yang sudah berjalan (production-safe).
 *
 * Perubahan:
 * 1. mutasi_tiket  → jenis_bayar_id dibuat nullable
 * 2. mutasi_tiket  → tambah kolom nama_piutang (jika belum ada)
 * 3. topup_histories → jenis_bayar_id dibuat nullable
 */
return new class extends Migration
{
    public function up(): void
    {
        // =============================================
        // 1. PERBAIKI tabel mutasi_tiket
        // =============================================
        Schema::table('mutasi_tiket', function (Blueprint $table) {

            // Drop foreign key dulu sebelum ubah kolom
            $table->dropForeign(['jenis_bayar_id']);

            // Ubah jenis_bayar_id menjadi nullable
            $table->foreignId('jenis_bayar_id')
                ->nullable()
                ->change();

            // Tambah foreign key kembali dengan nullOnDelete
            $table->foreign('jenis_bayar_id')
                ->references('id')
                ->on('jenis_bayar')
                ->nullOnDelete();

            // Tambah kolom nama_piutang jika belum ada
            if (!Schema::hasColumn('mutasi_tiket', 'nama_piutang')) {
                $table->string('nama_piutang', 100)->nullable()->after('piutang_id');
            }
        });

        // =============================================
        // 2. PERBAIKI tabel topup_histories
        // =============================================
        Schema::table('topup_histories', function (Blueprint $table) {

            // Drop foreign key dulu sebelum ubah kolom
            $table->dropForeign(['jenis_bayar_id']);

            // Ubah jenis_bayar_id menjadi nullable
            $table->foreignId('jenis_bayar_id')
                ->nullable()
                ->change();

            // Tambah foreign key kembali dengan nullOnDelete
            $table->foreign('jenis_bayar_id')
                ->references('id')
                ->on('jenis_bayar')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        // =============================================
        // ROLLBACK mutasi_tiket
        // =============================================
        Schema::table('mutasi_tiket', function (Blueprint $table) {
            $table->dropForeign(['jenis_bayar_id']);

            $table->foreignId('jenis_bayar_id')
                ->nullable(false)
                ->change();

            $table->foreign('jenis_bayar_id')
                ->references('id')
                ->on('jenis_bayar');

            if (Schema::hasColumn('mutasi_tiket', 'nama_piutang')) {
                $table->dropColumn('nama_piutang');
            }
        });

        // =============================================
        // ROLLBACK topup_histories
        // =============================================
        Schema::table('topup_histories', function (Blueprint $table) {
            $table->dropForeign(['jenis_bayar_id']);

            $table->foreignId('jenis_bayar_id')
                ->nullable(false)
                ->change();

            $table->foreign('jenis_bayar_id')
                ->references('id')
                ->on('jenis_bayar');
        });
    }
};
