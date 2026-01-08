<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPpob;
use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\PpobHistory;
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

        //dd($validated);

        try {
            DB::transaction(function () use ($validated) {
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
            });
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }


        return redirect()
            ->route('ppob.index')
            ->with('success', 'Data PPOB berhasil disimpan');
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
