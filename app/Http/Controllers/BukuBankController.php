<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BukuBankController extends Controller
{
    public function index()
    {
        $bukuBank = [
            (object)[
                'tanggal' => '2025-11-01',
                'debit' => '1,000,000',
                'kredit' => '0',
                'keterangan' => 'Setoran Awal',
            ],
            (object)[
                'tanggal' => '2025-11-03',
                'debit' => '0',
                'kredit' => '250,000',
                'keterangan' => 'Pembelian Tiket',
            ],
            (object)[
                'tanggal' => '2025-11-05',
                'debit' => '500,000',
                'kredit' => '0',
                'keterangan' => 'Top Up Saldo',
            ],
        ];
        return view('bukuBank', compact('bukuBank'));
    }
}
