<?php

namespace App\Http\Controllers;

use App\Models\Insentif;
use App\Models\JenisTiket;
use App\Models\JenisPpob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsentifController extends Controller
{
    public function index()
    {
        return view('insentif', [
            'insentif' => Insentif::orderBy('tgl', 'desc')->get(),
            'jenisTiket' => JenisTiket::all(),
            'jenisPpob'  => JenisPpob::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl'     => 'required|date',
            'jumlah'  => 'required|integer|min:1',
            'sumber'  => 'required|in:tiket,ppob',
            'jenis_tiket_id' => 'nullable|exists:jenis_tiket,id',
            'jenis_ppob_id'  => 'nullable|exists:jenis_ppob,id',
            'keterangan'     => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // SIMPAN INSENTIF
            $insentif = Insentif::create([
                'tgl'            => $request->tgl,
                'jumlah'         => $request->jumlah,
                'jenis_tiket_id' => $request->sumber === 'tiket' ? $request->jenis_tiket_id : null,
                'jenis_ppob_id'  => $request->sumber === 'ppob'  ? $request->jenis_ppob_id  : null,
                'keterangan'     => strtoupper($request->keterangan),
            ]);

            // TAMBAH SALDO
            if ($request->sumber === 'tiket') {
                $jenisTiket = JenisTiket::findOrFail($request->jenis_tiket_id);
                $jenisTiket->saldo += $request->jumlah;
                $jenisTiket->save();
            }

            if ($request->sumber === 'ppob') {
                $jenisPpob = JenisPpob::findOrFail($request->jenis_ppob_id);
                $jenisPpob->saldo += $request->jumlah;
                $jenisPpob->save();
            }

            DB::commit();

            return back()->with('success', 'Insentif berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan insentif');
        }
    }
}
