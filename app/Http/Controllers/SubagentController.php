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
            ->orderBy('created_at', 'asc')
            ->get();

        $saldo = 0;
        $histories->transform(function ($item) use (&$saldo) {
            $saldo += $item->transaksi;
            $item->saldo = $saldo;
            return $item;
        });

        $histories = $histories->sortByDesc('created_at')->values();

        return view('subagent', compact('subagents', 'histories', 'jenisBayar', 'bank'));
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