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
        // SALDO AWAL JENIS TIKET
        DB::table('jenis_tiket')->get()->each(function ($jt) {
            DB::table('jenis_tiket')
                ->where('id', $jt->id)
                ->update([
                    'saldo' => rand(50_000_000, 150_000_000),
                ]);
        });

        // SALDO AWAL JENIS PPOB
        DB::table('jenis_ppob')->get()->each(function ($jp) {
            DB::table('jenis_ppob')
                ->where('id', $jp->id)
                ->update([
                    'saldo' => rand(20000000, 100000000),
                ]);
        });

        for ($i = 1; $i <= 50; $i++) {

            DB::transaction(function () use ($i) {
                 /* ===============================
                   PILIH JENIS TIKET & PPOB
                =============================== */

                $jenisTiket = DB::table('jenis_tiket')->inRandomOrder()->first();
                $jenisPpob  = DB::table('jenis_ppob')->inRandomOrder()->first();

                $nta = rand(500_000, 1_500_000);
                $hargaJual = $nta + rand(50_000, 300_000);

                if ($jenisTiket->saldo < $hargaJual || $jenisPpob->saldo < $nta) {
                    return;
                }

                // ===============================
                // 1️⃣ TIKET
                // ===============================
                $kodeBooking = strtoupper(Str::random(10));

                DB::table('tiket')->insert([
                    'kode_booking'   => $kodeBooking,
                    'tgl_issued'     => now(),
                    'name'           => fake()->name(),
                    'nta'            => $nta,
                    'harga_jual'     => $hargaJual,
                    'komisi'         => $hargaJual - $nta,
                    'diskon'         => rand(0, 100000),
                    'rute'           => 'SUB-JKT',
                    'tgl_flight'     => now()->addDays(rand(5, 40)),
                    'status'         => 'issued',
                    'jenis_tiket_id' => $jenisTiket->id,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                // KURANGI SALDO JENIS TIKET
                DB::table('jenis_tiket')
                    ->where('id', $jenisTiket->id)
                    ->decrement('saldo', $hargaJual);

                // ===============================
                // 2️⃣ PEMBAYARAN ONLINE (PPOB)
                // ===============================
                $pembayaranOnlineId = DB::table('pembayaran_online')->insertGetId([
                    'tgl'           => now(),
                    'id_pel'        => 'PEL' . rand(100000, 999999),
                    'jenis_ppob_id' => $jenisPpob->id,
                    'nta'           => $nta,
                    'harga_jual'    => $hargaJual,
                    'komisi'        => $hargaJual - $nta,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);

                // KURANGI SALDO JENIS PPOB
                DB::table('jenis_ppob')
                    ->where('id', $jenisPpob->id)
                    ->decrement('saldo', $nta);

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
                    'bank_id'              => $jenisBayar === 1 ? rand(1, 5) : null,
                    'pembayaran_online_id' => $pembayaranOnlineId,
                    'tiket_kode_booking'   => $kodeBooking,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);
            });
        }
    }
}
