<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TiketController extends Controller
{
    /**
     * Tampilkan halaman input data tiket.
     */
    public function index()
    {
        $tikets = Tiket::orderBy('created_at', 'desc')->get();
        return view('input-data', compact('tikets'));
    }

    /**
     * Simpan data tiket baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_issued' => 'required|date',
            'kode_booking' => 'required|string|max:255|unique:tikets,kode_booking',
            'airlines' => 'required|string',
            'nama' => 'required|string',
            'rute1' => 'required|string',
            'tgl_flight1' => 'required|date',
            'rute2' => 'nullable|string',
            'tgl_flight2' => 'nullable|date',
            'harga' => 'required|numeric',
            'nta' => 'required|numeric',
            'diskon' => 'required|numeric',
            'pembayaran' => 'required|string',
            'nama_piutang' => 'nullable|string',
            'tgl_realisasi' => 'nullable|date',
            'nilai_refund' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);

        // Hitung otomatis komisi
        $validated['komisi'] = $validated['harga'] - $validated['nta'] - $validated['diskon'];

        // Isi jam input otomatis sesuai waktu server
        $validated['jam_input'] = Carbon::now()->format('H:i:s');

        // Isi user login atau Guest
        $validated['usr'] = Auth::check() ? Auth::user()->name : 'Guest';

        // Simpan ke database
        $tiket = Tiket::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data tiket berhasil disimpan.',
            'data' => $tiket
        ]);
    }

    /**
     * Ambil semua data tiket dalam bentuk JSON.
     */
    public function getAll()
    {
        $tikets = Tiket::orderBy('created_at', 'desc')->get();
        return response()->json($tikets);
    }

    /**
     * Tampilkan halaman invoice berdasarkan ID tiket.
     */
    public function showInvoice(Request $request)
    {
        $ids = $request->query('ids');
        $idArray = explode(',', $ids);

        $data = Tiket::whereIn('id', $idArray)->get();

        return view('invoice', compact('data'));
    }
}
