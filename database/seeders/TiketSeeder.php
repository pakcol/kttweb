<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TiketSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'kode_booking'   => 'AB123CD456',
                'tgl_issued'     => Carbon::now()->subDays(5),
                'name'           => 'Budi Santoso',
                'nta'            => 1200000,
                'harga_jual'     => 1350000,
                'diskon'         => null,
                'rute'           => 'SUB-JKT',
                'tgl_flight'     => Carbon::now()->addDays(10),
                'rute2'          => null,
                'tgl_flight2'    => null,
                'status'         => 'issued',
                'jenis_tiket_id' => 1,
                'keterangan'     => 'Tiket PP ekonomi',
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'kode_booking'   => 'EF789GH012',
                'tgl_issued'     => Carbon::now()->subDays(2),
                'name'           => 'Siti Aminah',
                'nta'            => 950000,
                'harga_jual'     => 1100000,
                'diskon'         => 'PROMO10',
                'rute'           => 'JKT-DPS',
                'tgl_flight'     => Carbon::now()->addDays(20),
                'rute2'          => 'DPS-JKT',
                'tgl_flight2'    => Carbon::now()->addDays(25),
                'status'         => 'pending',
                'jenis_tiket_id' => 2,
                'keterangan'     => null,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        DB::table('tiket')->insert($data);
    }
}
