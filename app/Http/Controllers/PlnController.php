<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pln;

class PlnController extends Controller
{
    public function index() {
        $data = Pln::all();
        return view('pln', compact('data'));
    }

    public function store(Request $request) {
        Pln::create($request->all());
        return redirect()->route('pln.index')->with('success', 'Data PLN berhasil disimpan!');
    }
}
