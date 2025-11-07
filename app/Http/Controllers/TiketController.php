<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            'tglIssued'     => 'required|date',
            'kodeBooking'   => 'required|string|max:255|unique:ticket,kodeBooking',
            'airlines'      => 'required|string',
            'nama'          => 'required|string',
            'rute1'         => 'required|string',
            'tglFlight1'    => 'required|date',
            'rute2'         => 'nullable|string',
            'tglFlight2'    => 'nullable|date',
            'harga'         => 'required|numeric',
            'nta'           => 'required|numeric',
            'diskon'        => 'required|numeric',
            'pembayaran'    => 'required|string',
            'namaPiutang'   => 'nullable|string',
            'tglRealisasi'  => 'nullable|date',
            'nilaiRefund'   => 'nullable|numeric',
            'keterangan'    => 'nullable|string',
        ]);

        $validated['komisi'] = $validated['harga'] - $validated['nta'] - $validated['diskon'];
        $validated['jam'] = Carbon::now()->format('H:i');
        $validated['username'] = Auth::check() ? Auth::user()->username ?? Auth::user()->name : 'Guest';

        $tiket = Tiket::create($validated);

        return redirect()->route('input-data.index')->with('success', 'Data tiket berhasil disimpan.');
    }

    public function getAll()
    {
        $tikets = Tiket::orderBy('created_at', 'desc')->get();
        return response()->json($tikets);
    }

    public function showInvoice(Request $request)
    {
        $ids = explode(',', $request->query('ids'));
        $data = Tiket::whereIn('id', $ids)->get();

        return view('invoice', compact('data'));
    }
}
