<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Evi; // pastikan model ini ada dan sesuai
use Illuminate\Support\Facades\Validator;

class PiutangController extends Controller
{
    public function index()
    {
        // Ambil semua data dari tabel evi
        $data = Evi::all();

        // Kirim ke view piutang.blade.php
        return view('piutang', compact('data'));
    }

    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'tgl' => 'required|date',
            'topup' => 'nullable|integer',
            'jam' => 'nullable',
            'kodeBooking' => 'nullable|string|max:45',
            'airlines' => 'nullable|string|max:45',
            'nama' => 'nullable|string|max:45',
            'rute1' => 'nullable|string|max:45',
            'tglFlight1' => 'nullable|date',
            'rute2' => 'nullable|string|max:45',
            'tglFlight2' => 'nullable|date',
            'harga' => 'nullable|integer',
            'nta' => 'nullable|integer',
            'keterangan' => 'nullable|string|max:300',
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
