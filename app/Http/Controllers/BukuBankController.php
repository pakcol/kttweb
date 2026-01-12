<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\TopupHistory;
use App\Models\MutasiBank;
use App\Models\JenisBayar;
use Illuminate\Support\Facades\DB;

class BukuBankController extends Controller
{
    public function index(Request $request)
    {
        $bankList = Bank::orderBy('name')->get();
        $bankId = $request->bank_id ?? $bankList->first()?->id;

        /* ================= BUKU BANK DARI TABEL MUTASI_BANK ================= */
        $bukuBank = DB::table('mutasi_bank')
                ->where('bank_id', $bankId)
                ->orderBy('tanggal', 'asc')   
                ->orderBy('id', 'asc')     
                ->get();



        /* ================= SALDO BERJALAN (BERDASARKAN KOLOM DEBIT/KREDIT) ================= */
        $saldo = 0;
        $bukuBank = $bukuBank->map(function ($row) use (&$saldo) {
                $saldo += ($row->debit ?? 0) - ($row->kredit ?? 0);
                $row->saldo = $saldo;
                return $row;
            });


        return view('buku-bank', [
            'bankList' => $bankList,
            'bankId'   => $bankId,
            'bukuBank' => $bukuBank,
            'saldo'    => $saldo,
            'banks'    => Bank::all(),
        ]);
    }


    public function topUp(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'bank_id' => 'required|exists:bank,id',
            'nominal' => 'required|integer|min:1000',
            'keterangan' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {

            $bank = Bank::lockForUpdate()->findOrFail($request->bank_id);

            // 1️⃣ Hitung saldo bank sesudah setor
            $saldoSesudah = $bank->saldo + $request->nominal;

            // 1️⃣ Simpan history ke TOPUP_HISTORIES
            TopupHistory::create([
                'tgl_issued'     => $request->tanggal,
                'transaksi'      => $request->nominal,
                'jenis_tiket_id' => null,
                'subagent_id'    => null,
                'jenis_bayar_id' => 1, // BANK
                'bank_id'        => $bank->id,
                'keterangan'     => $request->keterangan ?? 'Top up saldo bank '.$bank->name,
            ]);

            // 2️⃣ Catat ke MUTASI_BANK (uang MASUK ke bank)
            MutasiBank::create([
                'bank_id'    => $bank->id,
                'tanggal'    => $request->tanggal,
                'debit'      => $request->nominal,
                'kredit'     => 0,
                'saldo'      => $saldoSesudah,
                'keterangan' => $request->keterangan ?? 'Top up saldo bank '.$bank->name,
            ]);

            // 3️⃣ Update saldo bank
            $bank->update([
                'saldo' => $saldoSesudah,
            ]);

            // 4️⃣ Ikutkan saldo pada jenis_bayar BANK (id = 1) bertambah
            $jenisBayarBank = JenisBayar::lockForUpdate()->findOrFail(1);
            $jenisBayarBank->increment('saldo', $request->nominal);
        });

        return redirect()
            ->back()
            ->with('success', 'Top up bank berhasil & tercatat di biaya');
    }

    public function store(Request $request)
    {
        DB::transaction(function () use ($request) {

            $setoranBanks = $request->input('setoran', []);
            $totalSetoran = 0;

            foreach ($setoranBanks as $bankId => $nominal) {

                $nominal = (int) $nominal;
                if ($nominal <= 0) continue;

                /** ======================
                 *  BANK
                 *  ====================== */
                $bank = Bank::lockForUpdate()->findOrFail($bankId);

                $saldoAwal = $bank->saldo;
                $saldoAkhir = $saldoAwal + $nominal;

                // update saldo bank
                $bank->update([
                    'saldo' => $saldoAkhir
                ]);

                /** ======================
                 *  MUTASI BANK
                 *  ====================== */
                MutasiBank::create([
                    'bank_id'   => $bank->id,
                    'tanggal'   => now(),
                    'debit'     => $nominal,
                    'kredit'    => 0,
                    'saldo'     => $saldoAkhir,
                    'keterangan'=> 'Setoran Tutup Kas',
                ]);

                $totalSetoran += $nominal;
            }

            /** ======================
             *  JENIS BAYAR (BANK = ID 1)
             *  ====================== */
            if ($totalSetoran > 0) {
                $jenisBayarBank = JenisBayar::lockForUpdate()->findOrFail(1);

                $jenisBayarBank->update([
                    'saldo' => $jenisBayarBank->saldo + $totalSetoran
                ]);
            }

        });

        return redirect()->back()->with('success', 'Setoran berhasil disimpan');
    }
}
