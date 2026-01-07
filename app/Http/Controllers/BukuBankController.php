<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Biaya;
use Illuminate\Support\Facades\DB;

class BukuBankController extends Controller
{
    public function index(Request $request)
    {
        $bankList = Bank::orderBy('name')->get();
        $bankId = $request->bank_id ?? $bankList->first()?->id;

        /* ================= NOTA (KREDIT / MASUK) ================= */
        $mutasiTiket = DB::table('mutasi_tiket')
            ->leftJoin('tiket', 'mutasi_tiket.tiket_kode_booking', '=', 'tiket.kode_booking')
            ->leftJoin('jenis_tiket', 'tiket.jenis_tiket_id', '=', 'jenis_tiket.id')
            ->where('mutasi_tiket.jenis_bayar_id', 1) // BANK
            ->where('mutasi_tiket.bank_id', $bankId)
            ->whereNotNull('mutasi_tiket.tgl_bayar')
            ->select(
                'mutasi_tiket.id as order_id',
                'mutasi_tiket.tgl_bayar as tanggal',
                'mutasi_tiket.harga_bayar as kredit',
                DB::raw('0 as debit'),
                DB::raw("
                    CONCAT('Pembayaran Tiket ', jenis_tiket.name_jenis)
                    as keterangan
                ")
            )
            ->get();

        $ppobHistories = DB::table('ppob_histories')
            ->leftJoin('jenis_ppob', 'ppob_histories.jenis_ppob_id', '=', 'jenis_ppob.id')
            ->where('ppob_histories.jenis_bayar_id', 1) // BANK
            ->where('ppob_histories.bank_id', $bankId)
            ->whereNotNull('ppob_histories.harga_jual')
            ->select(
                'ppob_histories.id as order_id',
                'ppob_histories.tgl as tanggal',
                'ppob_histories.harga_jual as kredit',
                DB::raw('0 as debit'),
                DB::raw("
                    CONCAT('Pembayaran PPOB ', jenis_ppob.jenis_ppob)
                    as keterangan
                ")
            )
            ->get();




        /* ================= BIAYA TOP UP (DEBIT / KELUAR) ================= */

        $kredit = DB::table('biaya')
            ->where('kategori', 'top_up')
            ->whereNull('id_jenis_tiket')
            ->where('jenis_bayar_id', 1) // BANK
            ->where('bank_id', $bankId)
            ->select(
                'biaya.id as order_id',
                'tgl as tanggal',
                'biaya as kredit',
                DB::raw('0 as debit'),
                'keterangan'
            )
            ->get();

        $debitTopupTiket = DB::table('biaya')
            ->where('kategori', 'top_up')
            ->whereNotNull('id_jenis_tiket')
            ->where('jenis_bayar_id', 1) // BANK
            ->where('bank_id', $bankId)
            ->select(
                'biaya.id as order_id',
                'tgl as tanggal',
                DB::raw('0 as kredit'),
                'biaya as debit',
                'keterangan'
            )
            ->get();


        $debitLainnya = DB::table('biaya')
            ->where('kategori', 'lainnya')
            ->where('jenis_bayar_id', 1) // BANK
            ->where('bank_id', $bankId)
            ->select(
                'biaya.id as order_id',
                'tgl as tanggal',
                DB::raw('0 as kredit'),
                'biaya as debit',
                'keterangan'
            )
            ->get();


        /* ================= GABUNG & SORT ================= */
        $bukuBank = collect()
            ->merge($mutasiTiket)
            ->merge($ppobHistories)
            ->merge($kredit)
            ->merge($debitTopupTiket)
            ->merge($debitLainnya)
            ->sortBy([
                ['tanggal', 'asc'],
                ['order_id', 'asc'],
            ])
            ->values();



        /* ================= SALDO BERJALAN ================= */
        $saldo = 0;

        $bukuBank = $bukuBank
            ->reverse()
            ->map(function ($row) use (&$saldo) {
                $saldo += ($row->kredit ?? 0) - ($row->debit ?? 0);
                $row->saldo = $saldo;
                return $row;
            })
            ->reverse()
            ->values();


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

            // 1️⃣ Update saldo bank
            $bank->update([
                'saldo' => $bank->saldo + $request->nominal
            ]);

            // 2️⃣ Simpan history ke tabel BIAYA
            Biaya::create([
                'tgl' => now(),
                'biaya' => $request->nominal,
                'kategori' => 'top_up',
                'jenis_bayar_id' => 1,
                'bank_id' => $bank->id,
                'keterangan' => $request->keterangan ?? 'Top up saldo bank '.$bank->name,
            ]);
        });

        return redirect()
            ->back()
            ->with('success', 'Top up bank berhasil & tercatat di biaya');
    }
}
