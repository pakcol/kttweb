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
                'tgl_issued'     => now(),
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
                'tanggal'    => now(),
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
}
