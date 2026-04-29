<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('topup_histories', function (Blueprint $table) {
            $table->decimal('biaya_admin', 15, 2)->default(0)->after('transaksi');
        });
    }

    public function down()
    {
        Schema::table('topup_histories', function (Blueprint $table) {
            $table->dropColumn('biaya_admin');
        });
    }
};
