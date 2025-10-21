<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PiutangController extends Controller
{
    public function store(Request $request)
    {
        $v = Validator::make($request->all(), [
            'TGL_ISSUED' => 'required|date',
            'JAM' => 'nullable|string',
            'KODEBOKING' => 'nullable|string',
            'AIRLINES' => 'nullable|string',
            'NAMA' => 'nullable|string',
            'RUTE1' => 'nullable|string',
            'TGL_FLIGHT1' => 'nullable|date',
            'RUTE2' => 'nullable|string',
            'TGL_FLIGHT2' => 'nullable|date',
            'HARGA' => 'nullable|numeric',
            'KETERANGAN' => 'nullable|string',
        ]);

        if ($v->fails()) {
            return response()->json(['errors' => $v->errors()], 422);
        }

        $last = Evi::orderBy('id', 'desc')->first();
        $lastSaldo = $last ? floatval($last->SALDO) : 0.0;

        $topUp = $request->input('TOP_UP') ? floatval($request->input('TOP_UP')) : 0.0;

        $newSaldo = $lastSaldo + $topUp;

        $data = $request->only([
            'TGL_ISSUED','JAM','KODEBOKING','AIRLINES','NAMA','RUTE1','TGL_FLIGHT1',
            'RUTE2','TGL_FLIGHT2','HARGA','NTA','TOP_UP','KETERANGAN','USR'
        ]);

        $data['TOP_UP'] = $topUp;
        $data['SALDO'] = $newSaldo;

        $evi = Evi::create($data);

        return response()->json(['message' => 'EVI saved', 'data' => $evi]);
    }
}
