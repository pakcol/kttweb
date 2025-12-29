<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JenisPpob;

class JenisPpobSeeder extends Seeder
{
    public function run(): void
    {
        JenisPpob::insert([
            [
                'jenis_ppob' => 'PLN',
                'saldo' => 0,
                'keterangan' => 'Pembayaran dan pembelian token listrik PLN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_ppob' => 'MKIOS',
                'saldo' => 0,
                'keterangan' => 'Pulsa dan paket data (MKIOS Telkomsel)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
