<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JenisBayarSeeder extends Seeder
{
    public function run(): void
    {
        $jenisBayar = [
            'BANK',
            'CASH',
            'PIUTANG',
        ];

        foreach ($jenisBayar as $nama) {
            DB::table('jenis_bayar')->updateOrInsert(
                ['nama' => $nama],
                [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}