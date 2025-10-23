<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BiayaController extends Controller
{
    public function index()
    {
        return view('biaya');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'biaya' => 'required|numeric',
            'pembayaran' => 'required|string',
            'keterangan' => 'required|string'
        ]);


        return redirect()->back()->with('success', 'Data biaya berhasil disimpan!');
    }
}
