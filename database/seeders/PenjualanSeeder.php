<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('penjualan')->insert([
            'TANGGAL' => now(),
            'JAM' => now()->format('H:i'),
            'TTL_PENJUALAN' => 0,
            'USR' => 'system',
        ]);
    }
}
