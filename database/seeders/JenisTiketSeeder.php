<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JenisTiketSeeder extends Seeder
{
    public function run(): void
    {
        $maskapai = [
            'LION',
            'GARUDA',
            'SRIWIJAYA',
            'CITILINK',
            'TRANSNUSA',
            'AIRASIA',
            'QGCORNER',
            'PELNI',
            'DLU',
        ];

        foreach ($maskapai as $nama) {
            DB::table('jenis_tiket')->insert([
                'nama'       => $nama,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
