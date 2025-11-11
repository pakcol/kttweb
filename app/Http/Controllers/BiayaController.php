<?php

namespace App\Http\Controllers;

use App\Models\Biaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BiayaController extends Controller
{
    public function index()
    {
        $biaya = Biaya::with('account')->orderBy('tgl', 'desc')->get();
        return view('biaya', compact('biaya'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl' => 'required|date',
            'jam' => 'required|date_format:H:i',
            'biaya' => 'required|integer',
            'pembayaran' => 'required|string',
            'keterangan' => 'required|string|max:20'
        ]);

        Biaya::create([
            'tgl' => $request->tgl,
            'jam' => $request->jam,
            'biaya' => (int)$request->biaya,
            'pembayaran' => $request->pembayaran,
            'keterangan' => $request->keterangan,
            'username' => Auth::user()->username ?? Auth::user()->name,
        ]);

        return redirect()->back()->with('success', 'Data biaya berhasil disimpan!');
    }
}
