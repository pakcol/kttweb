<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembayaranOnline;
use App\Models\JenisPpob;
use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\Nota;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class PpobController extends Controller
{
    public function index()
    {
        return view('ppob', [
            'ppob'       => PembayaranOnline::latest()->get(),
            'jenisPpob'  => JenisPpob::all(),
            'jenisBayar' => JenisBayar::all(),
            'jenisBayarNonPiutang' => JenisBayar::where('id', '!=', 3)->get(),
            'bank'       => Bank::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl'            => 'required|date',
            'nama'          => 'required|string|max:50',
            'id_pel'         => 'required|string|max:20',
            'jenis_ppob_id'  => 'required|exists:jenis_ppob,id',
            'nta'            => 'required|integer',
            'harga_jual'     => 'required|integer',
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|required_if:jenis_bayar_id,1|exists:bank,id',
        ]);

        //dd($validated);

        try {
            DB::transaction(function () use ($validated) {
                $ppob = PembayaranOnline::create([
                    'tgl'           => $validated['tgl'],
                    'id_pel'        => $validated['id_pel'],
                    'jenis_ppob_id' => $validated['jenis_ppob_id'],
                    'nta'           => $validated['nta'] ?? null,
                    'harga_jual'    => $validated['harga_jual'],
                ]);

                Nota::create([
                    'nama'                 => $validated['nama'],
                    'tgl_issued'           => now(),

                    //kalau piutang = null
                    'tgl_bayar' => (int) $validated['jenis_bayar_id'] === 3
                        ? null
                        : $validated['tgl'],
                    'harga_bayar'          => $ppob->harga_jual,
                    'jenis_bayar_id'       => $validated['jenis_bayar_id'],
                    'bank_id'              => $validated['bank_id'] ?? null,
                    'pembayaran_online_id' => $ppob->id,
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
            'nama'           => 'required|string|max:50',
            'id_pel'         => 'required|string|max:20',
            'jenis_ppob_id'  => 'required|exists:jenis_ppob,id',
            'nta'            => 'required|integer',
            'harga_jual'     => 'required|integer',
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|exists:bank,id',
        ]);

        DB::transaction(function () use ($validated, $id) {

            $ppob = PembayaranOnline::findOrFail($id);

            $ppob->update([
                'tgl'           => $validated['tgl'],
                'id_pel'        => $validated['id_pel'],
                'jenis_ppob_id' => $validated['jenis_ppob_id'],
                'nta'           => $validated['nta'],
                'harga_jual'    => $validated['harga_jual'],
            ]);

            $ppob->nota()->update([
                //kalau piutang = null
                'tgl_bayar' => (int) $validated['jenis_bayar_id'] === 3
                    ? null
                    : $validated['tgl'],
                'nama'           => $validated['nama'], 
                'harga_bayar'    => $ppob->harga_jual,
                'jenis_bayar_id' => $validated['jenis_bayar_id'],
                'bank_id'        => $validated['bank_id'] ?? null,
            ]);
        });

        return redirect()->route('ppob.index')->with('success', 'Data berhasil diperbarui');
    }

    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $ppob = PembayaranOnline::findOrFail($id);

            $ppob->nota()->delete();
            $ppob->delete();
        });

        return redirect()->route('ppob.index')->with('success', 'Data berhasil dihapus');
    }

    public function ppobPiutang()
    {
        // Ambil nota yang PIUTANG & belum lunas
        $notaPiutang = Nota::with([
                'pembayaranOnline.jenisPpob',
                'jenisBayar',
                'bank'
            ])
            ->whereHas('jenisBayar', function ($q) {
                $q->where('jenis', 'piutang');
            })
            ->whereNull('tgl_bayar')
            ->whereNotNull('pembayaran_online_id')
            ->get();

        // Ambil daftar nama unik untuk datalist
        $namaPiutang = $notaPiutang
            ->pluck('nama')
            ->filter()
            ->unique()
            ->values();
        
        $bank = Bank::all();
        $jenisBayarNonPiutang = JenisBayar::where('id', '!=', 3)->get();

        return view('ppobPiutang', [
            'ppob'    => $notaPiutang->pluck('pembayaranOnline')->filter(),
            'piutang' => $notaPiutang,          // ⬅️ DATA UTAMA
            'namaPiutangList' => $namaPiutang,   // ⬅️ UNTUK FORM
            'bank'   => $bank,
            'jenisBayarNonPiutang' => $jenisBayarNonPiutang,
        ]);
    }


}
