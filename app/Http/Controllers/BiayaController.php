<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\JenisBayar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiayaController extends Controller
{   
    public function index()
    {
        $biaya = Biaya::with(['jenisBayar'])
            ->orderBy('tgl', 'desc')
            ->get();

        // HANYA CASH
        $jenisBayar = JenisBayar::where('id', 2)->get();

        return view('biaya', compact('biaya', 'jenisBayar'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl'   => 'required|date',
            'biaya' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {

            // AMBIL CASH (id = 2)
            $jenisBayar = JenisBayar::lockForUpdate()->findOrFail(2);

            if ($jenisBayar->saldo < $request->biaya) {
                throw new \Exception('Saldo cash tidak mencukupi');
            }

            // SIMPAN BIAYA
            Biaya::create([
                'tgl'            => $request->tgl,
                'biaya'          => $request->biaya,
                'jenis_bayar_id' => 2, // CASH
                'keterangan'     => $request->keterangan,
            ]);

            // POTONG SALDO CASH
            $jenisBayar->update([
                'saldo' => $jenisBayar->saldo - $request->biaya,
                'debit' => $jenisBayar->debit + $request->biaya,
            ]);
        });

        return redirect()->back()->with('success', 'Data biaya (CASH) berhasil disimpan');
    }
}
