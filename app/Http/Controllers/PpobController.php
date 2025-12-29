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
                    'tgl_issued'           => now(),
                    'tgl_bayar'            => $ppob->tgl,
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
                'tgl_bayar'      => $ppob->tgl,
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

    public function indexPiutang()
    {
        $piutang = PembayaranOnline::piutang()
            ->orderBy('tgl', 'desc')
            ->orderBy('jam_realisasi', 'desc')
            ->get();
        
        // Ambil daftar nama piutang unik dari database
        $namaPiutangList = PembayaranOnline::piutang()
            ->whereNotNull('nama_piutang')
            ->where('nama_piutang', '!=', '')
            ->distinct()
            ->pluck('nama_piutang')
            ->sort()
            ->values();
        
        return view('ppobPiutang', compact('piutang', 'namaPiutangList'));
    }

    public function showPiutang($id)
    {
        if (Schema::hasTable('Pembayaran_online')) {
            $data = PembayaranOnline::piutang()->find($id);
        } else {
            // Dummy single data
            $dummy = collect([
                (object)[
                    'id' => 1,
                    'tanggal' => '2025-11-06',
                    'jam' => '10:45:00',
                    'id_pel' => 'PLN001',
                    'harga_jual' => 150000,
                    'transaksi' => 'TOKEN 100K',
                    'bayar' => 'PIUTANG',
                    'nama_piutang' => 'CV. Sinar Kupang',
                    'top_up' => 50000,
                    'insentif' => 2500,
                    'saldo' => 1000000,
                    'usr' => 'Admin1',
                    'tgl_realisasi' => '2025-11-07',
                    'jam_realisasi' => '14:20:00'
                ],
            ]);
            $data = $dummy->firstWhere('id', $id);
        }

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($data);
    }

    public function updatePiutang(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_piutang' => 'nullable|string',
            'harga_jual' => 'nullable|integer',
            'bayar' => 'nullable|string|max:45',
            'username' => 'nullable|string',
        ]);

        $pln = PembayaranOnline::piutang()->find($id);

        if (!$pln) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $pln->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => $pln
        ]);
    }
}
