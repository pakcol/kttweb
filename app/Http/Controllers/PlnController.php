<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pln;
use App\Models\PlnPiutang;
use Illuminate\Support\Facades\Schema;

class PlnController extends Controller
{
    public function index()
    {
        $data = Pln::all();
        return view('pln', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'required|date',
            'id_pel' => 'required|integer',
            'harga_jual' => 'required|integer',
            'transaksi' => 'nullable|integer',
            'bayar' => 'required|string|max:45',
            'nama_piutang' => 'nullable|string',
            'top_up' => 'nullable|integer',
            'insentif' => 'nullable|integer',
            'saldo' => 'nullable|integer',
            'tgl_reralisasi' => 'nullable|date',
            'jam_realisasi' => 'nullable',
        ]);

        $validated['username'] = auth()->user()->username ?? auth()->user()->name;

        Pln::create($validated);

        return redirect()->route('pln.index')->with('success', 'Data PLN berhasil disimpan!');
    }

    public function indexPiutang()
    {
        $piutang = Pln::piutang()
            ->orderBy('tgl', 'desc')
            ->orderBy('jam_realisasi', 'desc')
            ->get();
        
        // Ambil daftar nama piutang unik dari database
        $namaPiutangList = Pln::piutang()
            ->whereNotNull('nama_piutang')
            ->where('nama_piutang', '!=', '')
            ->distinct()
            ->pluck('nama_piutang')
            ->sort()
            ->values();
        
        return view('plnPiutang', compact('piutang', 'namaPiutangList'));
    }

    public function showPiutang($id)
    {
        if (Schema::hasTable('pln')) {
            $data = Pln::piutang()->find($id);
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

        $pln = Pln::piutang()->find($id);

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
