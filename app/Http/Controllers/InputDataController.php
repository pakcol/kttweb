<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InputDataController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');

        $tikets = Tiket::whereDate('tgl_issued', $today)
            ->orWhereDate('tgl_realisasi', $today)
            ->orderBy('tgl_issued')
            ->orderBy('jam_input') // pakai kolom jam_input
            ->get();

        return view('input-data', compact('tikets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_issued' => 'required|date',
            'kode_booking' => 'required|unique:tikets,kode_booking',
            'airlines' => 'required|string',
            'nama' => 'required|string',
            'rute1' => 'required|string',
            'tgl_flight1' => 'required|date',
            'harga' => 'required|numeric',
            'nta' => 'required|numeric',
            'diskon' => 'required|numeric',
            'pembayaran' => 'required|string',
        ]);

        $komisi = $request->harga - $request->nta - $request->diskon;

        Tiket::create([
            'tgl_issued' => $request->tgl_issued,
            'jam_input' => now()->format('H:i:s'), // pastikan jam_input diisi otomatis
            'kode_booking' => strtoupper($request->kode_booking),
            'airlines' => strtoupper($request->airlines),
            'nama' => strtoupper($request->nama),
            'rute1' => strtoupper($request->rute1),
            'tgl_flight1' => $request->tgl_flight1,
            'rute2' => $request->rute2 ? strtoupper($request->rute2) : null,
            'tgl_flight2' => $request->tgl_flight2,
            'harga' => $request->harga,
            'nta' => $request->nta,
            'diskon' => $request->diskon,
            'komisi' => $komisi,
            'pembayaran' => strtoupper($request->pembayaran),
            'nama_piutang' => $request->nama_piutang ? strtoupper($request->nama_piutang) : null,
            'tgl_realisasi' => $request->tgl_realisasi,
            'nilai_refund' => $request->nilai_refund ?? 0,
            'keterangan' => $request->keterangan ? strtoupper($request->keterangan) : null,
            'usr' => Auth::user()->name,
        ]);

        return redirect()->route('input-data.index')->with('success', 'Data berhasil disimpan!');
    }

    public function update(Request $request, $id)
    {
        $tiket = Tiket::findOrFail($id);

        $request->validate([
            'tgl_issued' => 'required|date',
            'kode_booking' => 'required|unique:tikets,kode_booking,' . $id,
            'airlines' => 'required|string',
            'nama' => 'required|string',
            'rute1' => 'required|string',
            'tgl_flight1' => 'required|date',
            'harga' => 'required|numeric',
            'nta' => 'required|numeric',
            'diskon' => 'required|numeric',
            'pembayaran' => 'required|string',
        ]);

        $komisi = $request->harga - $request->nta - $request->diskon;

        $tiket->update([
            'tgl_issued' => $request->tgl_issued,
            'jam_input' => now()->format('H:i:s'), // update jam_input juga saat update
            'kode_booking' => strtoupper($request->kode_booking),
            'airlines' => strtoupper($request->airlines),
            'nama' => strtoupper($request->nama),
            'rute1' => strtoupper($request->rute1),
            'tgl_flight1' => $request->tgl_flight1,
            'rute2' => $request->rute2 ? strtoupper($request->rute2) : null,
            'tgl_flight2' => $request->tgl_flight2,
            'harga' => $request->harga,
            'nta' => $request->nta,
            'diskon' => $request->diskon,
            'komisi' => $komisi,
            'pembayaran' => strtoupper($request->pembayaran),
            'nama_piutang' => $request->nama_piutang ? strtoupper($request->nama_piutang) : null,
            'tgl_realisasi' => $request->tgl_realisasi,
            'nilai_refund' => $request->nilai_refund ?? 0,
            'keterangan' => $request->keterangan ? strtoupper($request->keterangan) : null,
            'usr' => Auth::user()->name,
        ]);

        return redirect()->route('input-data.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $tiket = Tiket::findOrFail($id);
        $tiket->delete();

        return redirect()->route('input-data.index')->with('success', 'Data berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $search = strtoupper($request->search);

        if (strlen($search) < 3) {
            return redirect()->route('input-data.index')->with('error', 'Karakter pencarian minimal 3 huruf!');
        }

        $tikets = Tiket::where('kode_booking', 'like', "%{$search}%")
            ->orWhere('nama', 'like', "%{$search}%")
            ->get();

        return view('input-data', compact('tikets'));
    }

    public function getTiket($id)
    {
        $tiket = Tiket::findOrFail($id);
        return response()->json($tiket);
    }
}
