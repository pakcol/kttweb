<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DummyTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        for ($i = 1; $i <= 50; $i++) {

            DB::transaction(function () use ($i) {

                // ===============================
                // 1️⃣ TIKET
                // ===============================
                $kodeBooking = strtoupper(Str::random(10));

                DB::table('tiket')->insert([
                    'kode_booking'   => $kodeBooking,
                    'tgl_issued'     => Carbon::now()->subDays(rand(1, 30)),
                    'name'           => fake()->name(),
                    'nta'            => $nta = rand(500_000, 1_500_000),
                    'harga_jual'     => $hargaJual = $nta + rand(50_000, 300_000),
                    'komisi'         => $hargaJual-$nta,
                    'diskon'         => rand(0, 1) ? 'PROMO' : null,
                    'rute'           => 'SUB-JKT',
                    'tgl_flight'     => Carbon::now()->addDays(rand(5, 40)),
                    'rute2'          => null,
                    'tgl_flight2'    => null,
                    'status'         => rand(0, 1) ? 'issued' : 'pending',
                    'jenis_tiket_id' => rand(1, 2),
                    'keterangan'     => 'Dummy tiket',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // ===============================
                // 2️⃣ PEMBAYARAN ONLINE (PPOB)
                // ===============================
                $jenisPpobId = DB::table('jenis_ppob')->inRandomOrder()->value('id');
                $pembayaranOnlineId = DB::table('pembayaran_online')->insertGetId([
                    'tgl'           => now(),
                    'id_pel'        => 'PEL' . rand(100000, 999999),
                    'jenis_ppob_id' => $jenisPpobId,
                    'nta'           => $nta,
                    'harga_jual'    => $hargaJual,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                // ===============================
                // 3️⃣ NOTA
                // ===============================
                $jenisBayar = rand(1, 3); // 3 = PIUTANG

                DB::table('nota')->insert([
                    'nama'                 => fake()->name(),
                    'tgl_issued'           => now(),
                    'tgl_bayar'            => $jenisBayar === 3 ? null : now(),
                    'harga_bayar'          => $hargaJual,
                    'jenis_bayar_id'       => $jenisBayar,
                    'bank_id'              => $jenisBayar === 1 ? rand(1, 3) : null,
                    'pembayaran_online_id' => $pembayaranOnlineId,
                    'tiket_kode_booking'   => $kodeBooking,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);
            });
        }
    }
}
