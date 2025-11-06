<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RekapanController extends Controller
{
    public function index()
    {
        $data = [
            'penjualan' => 12500000,
            'piutang' => 2500000,
            'pengeluaran' => 3200000,
            'refund' => 500000,

            'transfer_bca' => 3000000,
            'transfer_bri' => 2000000,
            'transfer_bni' => 1500000,
            'transfer_btn' => 1000000,

            'saldo_airlines' => [
                'Citilink' => 1200000,
                'Garuda' => 1800000,
                'QGCorner' => 900000,
                'Lion' => 1400000,
                'Sriwijaya' => 700000,
                'Transnusa' => 800000,
                'Pelni' => 500000,
                'Air Asia' => 600000,
                'DLU' => 300000,
            ],

            'topup_airlines' => [
                'Citilink' => 600000,
                'Garuda' => 800000,
                'QGCorner' => 450000,
                'Lion' => 700000,
                'Sriwijaya' => 300000,
                'Transnusa' => 350000,
                'Pelni' => 200000,
                'Air Asia' => 250000,
                'DLU' => 150000,
            ],

            'saldo_subagent' => [
                'Evi' => 1000000,
                'Cash' => 500000,
            ],

            'setoran' => [
                'BCA' => 2000000,
                'BRI' => 1500000,
                'BNI' => 1200000,
                'MANDIRI' => 1800000,
            ]
        ];

        return view('rekapan', compact('data'));
    }
}
