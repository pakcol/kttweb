<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use App\Models\JenisBayar;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BiayaController extends Controller
{   

    public function index()
    {
        $biaya = Biaya::with(['jenisBayar', 'bank'])
            ->orderBy('tgl', 'desc')
            ->get();

        $jenisBayar = JenisBayar::orderBy('jenis')->get();

        $bank = Bank::orderBy('name')->get();

        return view('biaya', compact(
            'biaya',
            'jenisBayar',
            'bank'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl' => 'required|date',
            'biaya' => 'required|numeric|min:1',
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id' => 'nullable|exists:bank,id',
            'keterangan' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {

            $biaya = Biaya::create([
                'tgl' => $request->tgl,
                'biaya' => $request->biaya,
                'jenis_bayar_id' => $request->jenis_bayar_id,
                'bank_id' => $request->bank_id,
                'keterangan' => $request->keterangan,
            ]);

            // 🔥 POTONG SALDO BANK JIKA ADA
            if ($request->bank_id) {
                $bank = Bank::findOrFail($request->bank_id);

                if ($bank->saldo < $request->biaya) {
                    throw new \Exception('Saldo bank tidak mencukupi');
                }

                $bank->update([
                    'saldo' => $bank->saldo - $request->biaya,
                    'debit' => $bank->debit + $request->biaya,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Data biaya berhasil disimpan');
    }

}