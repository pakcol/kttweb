<?php

namespace App\Http\Controllers;

use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\Biaya;
use App\Models\Tiket;
use App\Models\Subagent;
use App\Models\SubagentHistory;
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
            'tgl_issued' => 'required',
            'kode_booking' => 'required|unique:tiket,kode_booking',
            'name' => 'required',
            'rute' => 'required',
            'tgl_flight' => 'required',
            'nta' => 'required|numeric|min:1',
            'jenis_tiket_id' => 'required',
            'subagent_id' => 'required'
        ]);

        DB::transaction(function () use ($request) {

            $subagent = Subagent::lockForUpdate()->findOrFail($request->subagent_id);

            // ❌ kalau saldo tidak cukup
            if ($subagent->saldo < $request->nta) {
                throw new \Exception('Saldo subagent tidak mencukupi');
            }

            // 1️⃣ SIMPAN TIKET
            $tiket = Tiket::create([
                'tgl_issued' => $request->tgl_issued,
                'kode_booking' => strtoupper($request->kode_booking),
                'name' => strtoupper($request->name),
                'rute' => strtoupper($request->rute),
                'rute2' => strtoupper($request->rute2),
                'tgl_flight' => $request->tgl_flight,
                'tgl_flight2' => $request->tgl_flight2,
                'nta' => $request->nta,
                'harga_jual' => $request->nta, // ⬅️ otomatis
                'diskon' => $request->diskon ?? 0,
                'komisi' => $request->komisi ?? 0,
                'status' => 'issued',
                'jenis_tiket_id' => $request->jenis_tiket_id,
                'keterangan' => 'SUBAGENT',
            ]);

            // 2️⃣ KURANGI SALDO SUBAGENT
            $subagent->saldo -= $request->nta;
            $subagent->save();

            // 2️⃣ CATAT MUTASI SUBAGENT
            SubagentHistory::create([
                'tgl_issued' => now(),
                'subagent_id' => $request->subagent_id,
                'kode_booking' => $tiket->kode_booking,
                'status' => 'pesan_tiket',
                'transaksi' => -$request->nta, // ⬅️ POTONG saldo
            ]);
        });

        return redirect()->back()->with('success', 'Tiket subagent berhasil disimpan');
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

            $subagent = Subagent::lockForUpdate()->findOrFail($request->subagent_id);

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
                'kategori' => 'top_up',
                'jenis_bayar_id' => $request->jenis_bayar_id,
                'bank_id' => $request->bank_id,
                'keterangan' => 'Top Up Subagent: ' . $subagent->nama,
            ]);

            // 2️⃣ simpan history
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