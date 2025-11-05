<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlnPiutang;

class PlnPiutangController extends Controller
{
    public function index()
    {
        $piutang = PlnPiutang::all();
        return view('plnPiutang', compact('piutang'));
    }

    public function show($id)
    {
        $data = PlnPiutang::find($id);
        return response()->json($data);
    }
}
