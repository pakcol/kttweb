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
                'keterangan' => 'Pembayaran dan pembelian token listrik PLN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_ppob' => 'BPJS',
                'keterangan' => 'Pembayaran BPJS',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_ppob' => 'INDIHOME',
                'keterangan' => 'Pembayaran WiFi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_ppob' => 'PDAM',
                'keterangan' => 'Pembayaran Air',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'jenis_ppob' => 'TOP_UP',
                'keterangan' => 'Untuk Top Up PPOB.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
