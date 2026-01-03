<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bank;
use App\Models\Biaya;
use Illuminate\Support\Facades\DB;

class BukuBankController extends Controller
{
    public function index()
    {
        return view('bukuBank', [
            'banks' => Bank::orderBy('name')->get()
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
