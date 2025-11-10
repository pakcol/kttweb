<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tiket;
use Illuminate\Support\Facades\Validator;

class PiutangController extends Controller
{
    public function index()
    {
        // Ambil daftar nama piutang unik dari tabel ticket dengan pembayaran = 'PIUTANG'
        $namaPiutangList = Tiket::where('pembayaran', 'PIUTANG')
            ->whereNotNull('namaPiutang')
            ->where('namaPiutang', '!=', '')
            ->distinct()
            ->pluck('namaPiutang')
            ->sort()
            ->values();

        // Ambil semua data piutang dari tabel ticket
        $data = Tiket::where('pembayaran', 'PIUTANG')
            ->orderBy('tglIssued', 'desc')
            ->orderBy('jam', 'desc')
            ->get();

        // Kirim ke view piutang.blade.php
        return view('piutang', compact('data', 'namaPiutangList'));
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tgl' => 'nullable|date',
            'nama_piutang' => 'nullable|string',
            'total_piutang' => 'nullable|integer',
            'harga' => 'nullable|integer',
            'total_diskon' => 'nullable|integer',
            'diskon' => 'nullable|integer',
            'kode_booking' => 'nullable|string|max:45',
            'komisi' => 'nullable|integer',
            'nama' => 'nullable|string|max:45',
            'pembayaran' => 'nullable|string',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $last = Evi::orderBy('id', 'desc')->first();
        $lastSaldo = $last ? (int)$last->saldo : 0;

        $topUp = $request->input('topup') ? (int)$request->input('topup') : 0;
        $newSaldo = $lastSaldo + $topUp;

        $data = $request->only([
            'tgl', 'jam', 'kodeBooking', 'airlines', 'nama', 'rute1', 'tglFlight1',
            'rute2', 'tglFlight2', 'harga', 'nta', 'keterangan'
        ]);

        $data['topup'] = $topUp;
        $data['saldo'] = $newSaldo;
        $data['username'] = auth()->user()->username ?? auth()->user()->name;

        $evi = Evi::create($data);

        return response()->json(['message' => 'EVI saved', 'data' => $evi]);
    }
}
