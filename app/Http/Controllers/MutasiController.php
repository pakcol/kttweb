<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    public function index()
    {
        $data = collect();

        return view('mutasi', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'bank' => 'required|string',
            'topup' => 'nullable|numeric',
            'biaya' => 'nullable|numeric',
            'insentif' => 'nullable|numeric',
            'airlines' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);

        $table = $request->airlines;
        if (!DB::getSchemaBuilder()->hasTable($table)) {
            return back()->with('error', 'Tabel untuk maskapai ini belum tersedia di database.');
        }

        DB::table($table)->insert([
            'tanggal' => $request->tanggal,
            'bank' => $request->bank,
            'topup' => $request->topup ?? 0,
            'biaya' => $request->biaya ?? 0,
            'insentif' => $request->insentif ?? 0,
            'keterangan' => $request->keterangan ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('mutasi.show', ['airlines' => $table])
            ->with('success', 'Data berhasil disimpan!');
    }

    public function show($airlines)
    {
        if (!DB::getSchemaBuilder()->hasTable($airlines)) {
            return back()->with('error', 'Tabel tidak ditemukan!');
        }

        $data = DB::table($airlines)->orderBy('tanggal', 'desc')->get();

        return view('mutasi', compact('data'));
    }
}
