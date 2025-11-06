<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pln;
use App\Models\PlnPiutang;

class PlnController extends Controller
{
    public function index() {
        $data = Pln::all();
        return view('pln', compact('data'));
    }

    public function store(Request $request)
    {
        // Validasi opsional
        $validated = $request->validate([
            'no_pel' => 'required|string|max:255',
            'pulsa' => 'nullable|string',
            'nta' => 'nullable|string',
            'tgl' => 'nullable|date',
            'bayar' => 'required|string',
            'nama_piutang' => 'nullable|string',
        ]);

        Pln::create($validated);
        return redirect()->route('pln.index')->with('success', 'Data PLN berhasil disimpan!');
    }

    public function indexPiutang()
    {
        // Menggunakan scope dari model Pln
        $piutang = Pln::piutang()->get();

        return view('plnPiutang', compact('piutang'));
    }

    public function showPiutang($id)
    {
        // Pastikan hanya data piutang yang dicari
        $data = Pln::piutang()->find($id);

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($data);
    }
}
    