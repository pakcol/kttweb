<?php

namespace App\Http\Controllers;

use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\Tiket;
use App\Models\Subagent;
use App\Models\SubagentHistory;
use App\Models\TopupHistory;
use App\Models\MutasiBank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubagentController extends Controller
{
    public function index()
    {
        $jenisBayar = JenisBayar::whereNotIn('id', [3, 4])->get();
        $subagents = Subagent::orderBy('nama')->get();
        $bank = Bank::orderBy('name')->get();

        $histories = SubagentHistory::with('subagent')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('subagent', compact('subagents', 'histories', 'jenisBayar', 'bank'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_issued'     => 'required|date',
            'kode_booking'   => 'required',
            'name'           => 'required',
            'rute'           => 'required',
            'tgl_flight'     => 'required|date',
            'harga_jual'     => 'required|numeric|min:1',
            'nta'            => 'required|numeric|min:1',
            'jenis_tiket_id' => 'required',
            'subagent_id'    => 'required',
            'status'         => 'required|in:issued,refund',
        ]);

        DB::transaction(function () use ($request) {

            $subagent = Subagent::lockForUpdate()->findOrFail($request->subagent_id);

            /**
             * ===============================
             * MODE EDIT
             * ===============================
             */
            if ($request->filled('tiket_id')) {

                $tiket = Tiket::lockForUpdate()->findOrFail($request->tiket_id);

                $ntaLama = $tiket->nta;
                $ntaBaru = $request->nta;
                $selisih = $ntaBaru - $ntaLama;

                // 🚨 Jika issued → issued (koreksi)
                if ($tiket->status === 'issued' && $request->status === 'issued') {

                    if ($selisih > 0 && $subagent->saldo < $selisih) {
                        throw new \Exception('Saldo subagent tidak mencukupi');
                    }

                    $subagent->saldo -= $selisih;
                    $subagent->save();

                    SubagentHistory::create([
                        'tgl_issued'   => now(),
                        'subagent_id'  => $subagent->id,
                        'kode_booking' => $tiket->kode_booking,
                        'status'       => 'koreksi_tiket',
                        'transaksi'    => -$selisih,
                    ]);
                }

                // 🔄 Issued → Refund
                if ($tiket->status === 'issued' && $request->status === 'refund') {
                    $subagent->saldo += $ntaLama;
                    $subagent->save();

                    SubagentHistory::create([
                        'tgl_issued'   => now(),
                        'subagent_id'  => $subagent->id,
                        'kode_booking' => $tiket->kode_booking,
                        'status'       => 'refund',
                        'transaksi'    => $ntaLama,
                    ]);
                }

                // ❌ Refund tidak boleh diedit ulang
                if ($tiket->status === 'refund') {
                    throw new \Exception('Tiket refund tidak boleh diedit');
                }

                // UPDATE DATA TIKET
                $tiket->update([
                    'tgl_issued'     => $request->tgl_issued,
                    'name'           => strtoupper($request->name),
                    'rute'           => strtoupper($request->rute),
                    'rute2'          => strtoupper($request->rute2),
                    'tgl_flight'     => $request->tgl_flight,
                    'tgl_flight2'    => $request->tgl_flight2,
                    'nta'            => $ntaBaru,
                    'harga_jual'     => $request->harga_jual,
                    'diskon'         => $request->diskon ?? 0,
                    'komisi'         => $request->komisi ?? 0,
                    'status'         => $request->status,
                    'jenis_tiket_id' => $request->jenis_tiket_id,
                ]);

                return;
            }

            /**
             * ===============================
             * MODE CREATE
             * ===============================
             */
            if ($request->status === 'issued') {

                if ($subagent->saldo < $request->nta) {
                    throw new \Exception('Saldo subagent tidak mencukupi');
                }

                $tiket = Tiket::create([
                    'tgl_issued'     => $request->tgl_issued,
                    'kode_booking'   => strtoupper($request->kode_booking),
                    'name'           => strtoupper($request->name),
                    'rute'           => strtoupper($request->rute),
                    'rute2'          => strtoupper($request->rute2),
                    'tgl_flight'     => $request->tgl_flight,
                    'tgl_flight2'    => $request->tgl_flight2,
                    'nta'            => $request->nta,
                    'harga_jual'     => $request->harga_jual,
                    'diskon'         => $request->diskon ?? 0,
                    'komisi'         => $request->komisi ?? 0,
                    'status'         => 'issued',
                    'jenis_tiket_id' => $request->jenis_tiket_id,
                    'keterangan'     => 'SUBAGENT',
                ]);

                $subagent->saldo -= $request->nta;
                $subagent->save();

                SubagentHistory::create([
                    'tgl_issued'   => now(),
                    'subagent_id'  => $subagent->id,
                    'kode_booking' => $tiket->kode_booking,
                    'status'       => 'issued',
                    'transaksi'    => -$request->nta,
                ]);
            }

        });

        return back()->with('success', 'Data tiket berhasil diproses');
    }



    public function topup(Request $request)
    {
        $request->validate([
            'subagent_id' => 'required|exists:subagents,id',
            'nominal' => 'required|numeric|min:1',
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id' => 'nullable|exists:bank,id',
        ]);

        DB::transaction(function () use ($request) {

            $subagent   = Subagent::lockForUpdate()->findOrFail($request->subagent_id);
            $jenisBayar = JenisBayar::lockForUpdate()->findOrFail($request->jenis_bayar_id);

            $jenisBayar->increment('saldo', $request->nominal);
            if( $request->jenis_bayar_id == 1 && $request->bank_id ) {
                // jika jenis bayar adalah BANK, maka tambahkan saldo bank juga
                $bank = Bank::lockForUpdate()->findOrFail($request->bank_id);
                $bank->increment('saldo', $request->nominal);
            }

            // 2️⃣ JIKA SUMBER DARI BANK → KURANGI SALDO BANK
            $bankId = $request->jenis_bayar_id == 1 ? $request->bank_id : null;
            if ($bankId) {

                // catat mutasi bank: uang keluar untuk top up subagent
                MutasiBank::create([
                    'bank_id'    => $bank->id,
                    'tanggal'    => now(),
                    'ref_type'   => 'TOPUP_SUBAGENT',
                    'ref_id'     => $subagent->id,
                    'debit'      => $request->nominal,
                    'kredit'     => 0,
                    'saldo'      => $bank->saldo + $request->nominal,
                    'keterangan' => 'Top up saldo subagent ' . $subagent->nama,
                ]);

                $bank->decrement('saldo', $request->nominal);
            }

            // 3️⃣ TAMBAH SALDO SUBAGENT
            $subagent->increment('saldo', $request->nominal);

            // 4️⃣ CATAT KE TOPUP HISTORIES
            TopupHistory::create([
                'tgl_issued'     => now(),
                'transaksi'      => $request->nominal,
                'jenis_tiket_id' => null,
                'subagent_id'    => $subagent->id,
                'jenis_bayar_id' => $request->jenis_bayar_id,
                'bank_id'        => $bankId,
                'keterangan'     => 'Top Up Subagent: ' . $subagent->nama,
            ]);

            // 5️⃣ simpan history ke subagent_histories
            SubagentHistory::create([
                'tgl_issued'  => now(),
                'subagent_id' => $subagent->id,
                'status'      => 'top_up',
                'transaksi'   => $request->nominal,
                'keterangan'  => 'Top up saldo subagent '. $subagent->nama,
            ]);
        });

        return back()->with('success', 'Top up subagent berhasil');
    }
}