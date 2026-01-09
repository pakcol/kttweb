<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPpob;
use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\PpobHistory;
use App\Models\JenisTiket;
use App\Models\MutasiBank;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PpobController extends Controller
{
    public function index()
    {
        return view('ppob', [
            'ppob'       => PpobHistory::latest()->get(),
            'jenisPpob'  => JenisPpob::all(),
            'jenisBayar' => JenisBayar::where('id', '!=', 4)->get(),
            'jenisBayarNonPiutang' => JenisBayar::whereNotIn('id', [3, 4])->get(),
            'bank'       => Bank::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl'            => 'required|date',
            'id_pel'         => 'required|string|max:20',
            'jenis_ppob_id'  => 'required|exists:jenis_ppob,id',
            'nta'            => 'required|integer',
            'harga_jual'     => 'required|integer',
            'nama_piutang'   => 'nullable|string|max:100',
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|required_if:jenis_bayar_id,1|exists:bank,id',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $jenisBayar = JenisBayar::findOrFail($validated['jenis_bayar_id']);

                PpobHistory::create([
                    'tgl'           => $validated['tgl'],
                    'id_pel'        => $validated['id_pel'],
                    'jenis_ppob_id' => $validated['jenis_ppob_id'],
                    'nta'           => $validated['nta'] ?? null,
                    'harga_jual'    => $validated['harga_jual'],
                    'nama_piutang'  => $validated['nama_piutang'],
                    'jenis_bayar_id'=> $validated['jenis_bayar_id'],
                    'bank_id'       => $validated['bank_id'] ?? null,
                ]);

                // Update saldo jenis_bayar
                $jenisBayar->increment('saldo', $validated['harga_jual']);

                // Update saldo bank jika jenis_bayar_id = 1
                if ($validated['jenis_bayar_id'] == 1 && $validated['bank_id']) {
                    $bank = Bank::findOrFail($validated['bank_id']);
                    $bank->increment('saldo', $validated['harga_jual']);
                }
            });
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }

        return redirect()
            ->route('ppob.index')
            ->with('success', 'Data PPOB berhasil disimpan');
    }

    /**
     * Top up saldo PPOB (dicatat di ppob_histories sebagai jenis_ppob_id = 5, id_pel = 0)
     * dan MENGURANGI saldo jenis_bayar BANK serta saldo bank terkait.
     */
    public function topup(Request $request)
    {
        $validated = $request->validate([
            'tgl'            => 'required|date',
            'nominal'        => 'required|integer|min:1',
            'jenis_bayar_id' => 'required|in:1',
            'bank_id'        => 'nullable|exists:bank,id',
        ]);

        DB::transaction(function () use ($validated) {
            // 🔒 LOCK jenis bayar
            $jenisBayar = JenisBayar::lockForUpdate()->find(1); // Pastikan jenis_bayar_id=1 ada

            // 🔒 LOCK bank jika ada
            $bank = null;
            if ($validated['jenis_bayar_id'] == 1) { // Jika jenis_bayar_id=1
                $bank = Bank::lockForUpdate()->find($validated['bank_id']); // Pastikan bank_id ada
            }

            /**
             * 1️⃣ SIMPAN HISTORI PPOB
             */
            $saldoSebelumnya = PpobHistory::where('jenis_ppob_id', 5)
                ->where('jenis_bayar_id', 1)
                ->when($bank, function ($query) use ($bank) {
                    $query->where('bank_id', $bank->id);
                })
                ->sum('saldo');

            PpobHistory::create([
                'tgl'            => $validated['tgl'],
                'id_pel'         => 0,
                'jenis_ppob_id'  => 5,
                'nta'            => 0,
                'harga_jual'     => 0,
                'top_up'         => $validated['nominal'],
                'saldo'         => $saldoSebelumnya + $validated['nominal'],
                'jenis_bayar_id' => 1, // Set jenis_bayar_id=1
                'bank_id'        => $bank?->id ?? null,
            ]);

            /**
             * 2️⃣ TAMBAH SALDO JENIS BAYAR (UANG MASUK)
             */
            $jenisBayar->decrement('saldo', $validated['nominal']);

            /**
             * 3️⃣ JIKA bank → TAMBAH SALDO bank FISIK
             */
            if ($bank) {
                $bank->decrement('saldo', $validated['nominal']);
            }
        });

        return redirect()
            ->route('ppob.index')
            ->with('success', 'Topup PPOB berhasil !');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tgl'            => 'required|date',
            'id_pel'         => 'required|string|max:20',
            'jenis_ppob_id'  => 'required|exists:jenis_ppob,id',
            'nta'            => 'required|integer',
            'harga_jual'     => 'required|integer',
            'nama_piutang'   => 'nullable|string|max:100', // ✅ TAMBAHKAN
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|required_if:jenis_bayar_id,1|exists:bank,id',
        ]);

        DB::transaction(function () use ($validated, $id) {

            $ppob = PpobHistory::findOrFail($id);

            $ppob->update([
                'tgl'            => $validated['tgl'],
                'id_pel'         => $validated['id_pel'],
                'jenis_ppob_id'  => $validated['jenis_ppob_id'],
                'nta'            => $validated['nta'],
                'harga_jual'     => $validated['harga_jual'],
                'nama_piutang'   => $validated['nama_piutang'] ?? null,
                'jenis_bayar_id' => $validated['jenis_bayar_id'],
                'bank_id'        => $validated['bank_id'] ?? null,
            ]);
        });

        return redirect()
            ->route('ppob.index')
            ->with('success', 'Data berhasil diperbarui');
    }


    public function updatePiutang(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|required_if:jenis_bayar_id,1|exists:bank,id',
        ]);

        DB::transaction(function () use ($validated, $id) {

            $ppob = PpobHistory::findOrFail($id);

            $ppob->update([
                'jenis_bayar_id' => $validated['jenis_bayar_id'],
                'bank_id'        => $validated['bank_id'] ?? null
            ]);
        });

        return redirect()
            ->route('ppob.piutang')
            ->with('success', 'Piutang berhasil dibayar');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $ppob = PpobHistory::findOrFail($id);
            $ppob->delete();
        });

        return redirect()->route('ppob.index')->with('success', 'Data berhasil dihapus');
    }

    public function ppobPiutang()
    {
        $ppobPiutang = PpobHistory::with([
                'jenisPpob',
                'jenisBayar',
                'bank'
            ])
            ->whereHas('jenisBayar', function ($q) {
                $q->where('jenis', 'piutang');
            })
            ->get();

        $namaPiutang = $ppobPiutang
            ->pluck('nama_piutang')
            ->filter()
            ->unique()
            ->values();

        return view('ppobPiutang', [
            'piutang' => $ppobPiutang,          // ✅ DATA UTAMA
            'namaPiutangList' => $namaPiutang,
            'bank' => Bank::all(),
            'jenisBayarNonPiutang' => JenisBayar::where('id', '!=', 3)->get(),
        ]);
    }


}
