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
        $nota = DB::table('nota')
            ->leftJoin('pembayaran_online', 'nota.pembayaran_online_id', '=', 'pembayaran_online.id')
            ->leftJoin('jenis_ppob', 'pembayaran_online.jenis_ppob_id', '=', 'jenis_ppob.id')
            ->leftJoin('tiket', 'nota.tiket_kode_booking', '=', 'tiket.kode_booking')
            ->leftJoin('jenis_tiket', 'tiket.jenis_tiket_id', '=', 'jenis_tiket.id')
            ->where('nota.jenis_bayar_id', 1) // BANK
            ->where('nota.bank_id', $bankId)
            ->whereNotNull('nota.tgl_bayar')
            ->select(
                'nota.id as order_id',
                'nota.tgl_bayar as tanggal',
                'nota.harga_bayar as kredit',
                DB::raw('0 as debit'),
                DB::raw("
                    CASE
                        WHEN nota.pembayaran_online_id IS NOT NULL
                            THEN CONCAT('Pembayaran PPOB ', jenis_ppob.jenis_ppob)
                        WHEN nota.tiket_kode_booking IS NOT NULL
                            THEN CONCAT('Pembayaran Tiket ', jenis_tiket.name_jenis)
                        ELSE 'Pembayaran'
                    END as keterangan
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
            ->merge($nota)
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
