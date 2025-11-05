<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FindController extends Controller
{
    public function index()
    {
        return view('find');
    }

    public function search(Request $request)
    {
        $query = DB::table('penjualan');

        if ($request->filled('tgl_flight')) {
            $query->where(function($q) use ($request) {
                $q->whereDate('tgl_flight_1', $request->tgl_flight)
                  ->orWhereDate('tgl_flight_2', $request->tgl_flight);
            });
        }

        if ($request->filled('tgl_issued')) {
            $query->whereDate('tgl_issued', $request->tgl_issued);
        }

        if ($request->filled('tgl_realisasi')) {
            $query->whereDate('tgl_realisasi', $request->tgl_realisasi);
        }

        if ($request->filled('kode_booking')) {
            $query->where('kode_booking', 'like', '%'.$request->kode_booking.'%');
        }

        if ($request->filled('nama_pax')) {
            $query->where('nama', 'like', '%'.$request->nama_pax.'%');
        }

        if ($request->filled('nama_piutang')) {
            $query->where('nama_piutang', 'like', '%'.$request->nama_piutang.'%');
        }

        $query->orderBy('tgl_issued', 'desc')
              ->orderBy('jam', 'desc');

        $results = $query->get();

        return view('find', [
            'results' => $results,
            'request' => $request
        ]);
    }
}