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
            'kode_booking'   => 'required|string|max:10|unique:tiket,kode_booking',
            'name'           => 'required|string|max:100',
            'harga_jual'     => 'required|integer|min:0',
            'nta'            => 'required|integer|min:0',
            'diskon'         => 'required|integer|min:0',
            'komisi'         => 'required|integer|min:0',
            'rute'           => 'required|string|max:45',
            'tgl_flight'     => 'required|date',
            'jenis_tiket_id' => 'required|exists:jenis_tiket,id',
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|exists:bank,id',
            'nama_piutang'   => 'nullable|string|max:100',
            'keterangan'     => 'nullable|string|max:200',
            
            'subagent_id'    => 'required|exists:subagents,id',
        ]);

        DB::transaction(function () use ($request) {

            $subagent = Subagent::lockForUpdate()->findOrFail($request->subagent_id);

            if ($subagent->saldo < $request->nta) {
                throw new \Exception('Saldo subagent tidak cukup');
            }

            // potong saldo
            $subagent->decrement('saldo', $request->nta);

            // create tiket
            Tiket::create([
                'kode_booking'   => strtoupper($request->kode_booking),
                'name'           => $request->name,
                'rute'           => $request->rute,
                'tgl_issued'     => now(),
                'tgl_flight'     => $request->tgl_flight,
                'harga_jual'     => $request->harga_jual,
                'nta'            => $request->nta,
                'diskon'         => $request->diskon ?? 0,
                'komisi'         => $request->harga_jual - ($request->diskon ?? 0) - $request->nta,
                'status'         => 'issued',
                'jenis_tiket_id' => $request->jenis_tiket_id,
                'subagent_id'    => $subagent->id,
            ]);

            SubagentHistory::create([
                'tgl_issued'   => now(),
                'subagent_id'  => $subagent->id,
                'kode_booking' => strtoupper($request->kode_booking),
                'status'       => 'issued',
                'transaksi'    => -$request->nta,
            ]);
        });

        return back()->with('success', 'Tiket subagent berhasil dibuat');
    }


    public function update(Request $request, $kode_booking)
    {
        DB::transaction(function () use ($request, $kode_booking) {

            $tiket = Tiket::lockForUpdate()
                ->where('kode_booking', $kode_booking)
                ->firstOrFail();

            $subagent = Subagent::lockForUpdate()->findOrFail($request->subagent_id);

            // 🔄 ISSUED → REFUNDED
            if ($tiket->status === 'issued' && $request->status === 'refunded') {

                if (!$request->filled('nilai_refund') || !$request->filled('tgl_realisasi')) {
                    throw new \Exception('Nilai refund & tanggal realisasi wajib diisi');
                }

                $subagent->saldo += $request->nilai_refund;
                $subagent->save();

                SubagentHistory::create([
                    'tgl_issued'   => $request->tgl_realisasi,
                    'subagent_id'  => $subagent->id,
                    'kode_booking' => $tiket->kode_booking,
                    'status'       => 'refunded',
                    'transaksi'    => $request->nilai_refund,
                ]);
            }

            // 🚫 REFUND TIDAK BOLEH DIUBAH LAGI
            if ($tiket->status === 'refunded') {
                throw new \Exception('Tiket refund tidak bisa diedit ulang');
            }

            $tiket->update([
                'tgl_issued'     => $request->tgl_issued,
                'name'           => strtoupper($request->name),
                'rute'           => strtoupper($request->rute),
                'rute2'          => strtoupper($request->rute2),
                'tgl_flight'     => $request->tgl_flight,
                'tgl_flight2'    => $request->tgl_flight2,
                'harga_jual'     => $request->harga_jual,
                'diskon'         => $request->diskon ?? 0,
                'komisi'         => $request->komisi ?? 0,
                'status'         => $request->status,
                'nilai_refund'  => $request->nilai_refund,
                'tgl_realisasi' => $request->tgl_realisasi,
            ]);
        });

        return back()->with('success', 'Tiket berhasil diupdate');
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