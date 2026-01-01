<?php

namespace App\Http\Controllers;

use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\Biaya;
use App\Models\Subagent;
use App\Models\SubagentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubagentController extends Controller
{
    public function index()
    {
        $subagents = Subagent::orderBy('nama')->get();
        $jenisBayar = JenisBayar::orderBy('jenis')->get();
        $bank = Bank::orderBy('name')->get();

        $histories = SubagentHistory::with('subagent')
            ->orderBy('created_at', 'desc')
            ->get();
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

            $subagent = Subagent::findOrFail($request->subagent_id);

            // 1️⃣ UPDATE SALDO SUBAGENT
            $subagent->increment('saldo', $request->nominal);

            // 2️⃣ JIKA BANK → TAMBAH SALDO BANK
            if ($request->bank_id) {
                Bank::where('id', $request->bank_id)
                    ->increment('saldo', $request->nominal);
            }

            // 3️⃣ SIMPAN KE BIAYA (UANG MASUK)
            Biaya::create([
                'tgl' => now(),
                'biaya' => $request->nominal,
                'jenis_bayar_id' => $request->jenis_bayar_id,
                'bank_id' => $request->bank_id,
                'keterangan' => 'Top Up Subagent: ' . $subagent->name,
            ]);
        });

        return back()->with('success', 'Top up subagent berhasil');
    }
}