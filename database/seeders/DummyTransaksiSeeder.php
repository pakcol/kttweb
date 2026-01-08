<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Tiket;
use App\Models\MutasiTiket;
use App\Models\Bank;
use App\Models\Subagent;
use App\Models\SubagentHistory;
use App\Models\JenisTiket;

class DummyTransaksiSeeder extends Seeder
{
    public function run(): void
    {
        /* =====================================================
           1️⃣ SALDO AWAL FIXED (BANK, SUBAGENT, JENIS TIKET)
        ===================================================== */

        // BANK
        foreach (Bank::all() as $bank) {
            $bank->update([
                'saldo' => 10_000_000
            ]);
        }

        // SUBAGENT
        foreach (Subagent::all() as $subagent) {
            $subagent->update([
                'saldo' => 10_000_000
            ]);
        }

        // JENIS TIKET
        foreach (JenisTiket::all() as $jenis) {
            $jenis->update([
                'saldo'       => 10_000_000,
                'keterangan'  => $jenis->keterangan ?? 'DUMMY JENIS TIKET'
            ]);
        }

        /* =====================================================
           2️⃣ TIKET + MUTASI TIKET (50 DATA)
        ===================================================== */

        for ($i = 1; $i <= 50; $i++) {

            $harga = rand(800000, 2500000);
            $nta   = $harga - rand(50000, 200000);
            $jenisBayar = rand(1, 3); // 1 BANK | 2 CASH | 3 PIUTANG

            $kodeBooking = 'KB' . str_pad($i, 6, '0', STR_PAD_LEFT);

            // === TIKET ===
            Tiket::create([
                'kode_booking'   => $kodeBooking,
                'tgl_issued'     => Carbon::now()->subDays(rand(1, 30)),
                'name'           => 'PAX ' . $i,
                'nta'            => $nta,
                'harga_jual'     => $harga,
                'diskon'         => 0,
                'komisi'         => $harga - $nta,
                'rute'           => 'KUP-JKT',
                'tgl_flight'     => Carbon::now()->addDays(rand(1, 20)),
                'rute2'          => null,
                'tgl_flight2'    => null,
                'status'         => 'issued',
                'jenis_tiket_id' => JenisTiket::inRandomOrder()->first()->id,
                'nilai_refund'   => null,
                'tgl_realisasi'  => null,
                'keterangan'     => 'DUMMY TIKET',
            ]);

            // === MUTASI TIKET ===
            MutasiTiket::create([
                'tiket_kode_booking' => $kodeBooking,
                'tgl_issued'         => Carbon::now()->toDateString(),
                'tgl_bayar'          => $jenisBayar == 3 ? null : Carbon::now(),
                'harga_bayar'        => $harga,
                'insentif'           => rand(0, 50000),
                'jenis_bayar_id'     => $jenisBayar,
                'bank_id'            => $jenisBayar == 1 ? Bank::inRandomOrder()->first()->id : null,
                'nama_piutang'       => $jenisBayar == 3 ? 'PIUTANG ' . $kodeBooking : null,
                'keterangan'         => 'MUTASI TIKET',
            ]);
        }

        /* =====================================================
           3️⃣ SUBAGENT HISTORIES
           subagent_id = 1
        ===================================================== */

        $subagent = Subagent::find(1);

        if ($subagent) {

            // TOP UP AWAL
            for ($i = 0; $i < 3; $i++) {

                $nominal = 1_000_000;

                SubagentHistory::create([
                    'tgl_issued'   => Carbon::now()->subDays(30),
                    'subagent_id'  => $subagent->id,
                    'kode_booking' => null,
                    'status'       => 'top_up',
                    'transaksi'    => $nominal,
                ]);

                $subagent->increment('saldo', $nominal);
            }

            // PESAN TIKET (POTONG SALDO)
            $tickets = Tiket::take(20)->get();

            foreach ($tickets as $tiket) {

                SubagentHistory::create([
                    'tgl_issued'   => $tiket->tgl_issued,
                    'subagent_id'  => $subagent->id,
                    'kode_booking' => $tiket->kode_booking,
                    'status'       => 'pesan_tiket',
                    'transaksi'    => -$tiket->harga_jual,
                ]);

                $subagent->decrement('saldo', $tiket->harga_jual);
            }
        }
    }
}
