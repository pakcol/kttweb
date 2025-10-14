<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function show($id)
    {
        $data = DB::table('tikets')->where('id', $id)->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        return view('invoice', compact('data'));
    }
}
