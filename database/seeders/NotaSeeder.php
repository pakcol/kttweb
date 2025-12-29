<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotaSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama'                 => 'John Doe',
                'tgl_issued'           => Carbon::now()->subDays(5),
                'tgl_bayar'            => Carbon::now()->subDays(4),
                'harga_bayar'          => 1350000,
                'jenis_bayar_id'       => 1,
                'bank_id'              => 1,
                'pembayaran_online_id' => null,
                'tiket_kode_booking'   => 'AB123CD456',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'nama'                 => 'Jane Smith',
                'tgl_issued'           => Carbon::now()->subDays(2),
                'tgl_bayar'            => null,
                'harga_bayar'          => 1100000,
                'jenis_bayar_id'       => 2,
                'bank_id'              => null,
                'pembayaran_online_id' => null,
                'tiket_kode_booking'   => 'EF789GH012',
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
        ];

        DB::table('nota')->insert($data);
    }
}
