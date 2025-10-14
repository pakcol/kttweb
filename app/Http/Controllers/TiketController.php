<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TiketController extends Controller
{
    public function index()
    {
        $tikets = Tiket::orderBy('created_at', 'desc')->get();
        return view('input-data', compact('tikets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_issued' => 'required|date',
            'kode_booking' => 'required|string|max:255',
            'airlines' => 'required|string',
            'nama' => 'required|string',
            'rute1' => 'required|string',
            'tgl_flight1' => 'required|date',
            'harga' => 'required|numeric',
            'nta' => 'required|numeric',
            'diskon' => 'required|numeric',
            'pembayaran' => 'required|string'
        ]);

        $validated['komisi'] = $validated['harga'] - $validated['nta'] - $validated['diskon'];
        $validated['usr'] = Auth::check() ? Auth::user()->name : 'Guest';

        $tiket = Tiket::create($validated);

        return response()->json(['success' => true, 'data' => $tiket]);
    }

    public function getAll()
    {
        $tikets = Tiket::orderBy('created_at', 'desc')->get();
        return response()->json($tikets);
    }

    public function showInvoice(Request $request)
    {
        $ids = $request->query('ids'); 
        $idArray = explode(',', $ids);
        $data = Tiket::whereIn('id', $idArray)->get();

        return view('invoice', compact('data'));
    }
}
