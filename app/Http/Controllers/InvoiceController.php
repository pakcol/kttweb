<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function showSingle($id)
    {
        $tiket = DB::table('tikets')->where('id', $id)->first();

        if (!$tiket) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $tikets = collect([$tiket]);

        $subtotal = $tiket->harga ?? 0;
        $issued_fee = 25000;
        $materai = 10000;

        $total = $subtotal + $issued_fee + $materai;
        $terbilang = $this->terbilang($total) . ' Rupiah';

        return view('invoice', compact('tikets', 'subtotal', 'issued_fee', 'materai', 'total', 'terbilang'));
    }
    public function showMulti(Request $request)
    {
        $ids = $request->query('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu tiket untuk cetak invoice!');
        }

        $idsArray = array_map('intval', explode(',', $ids));
        $tikets = DB::table('tikets')->whereIn('id', $idsArray)->get();

        if ($tikets->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }
        $subtotal = $tikets->sum('harga');
        $issued_fee = 25000;
        $materai = 10000;
        $total = $subtotal + $issued_fee + $materai;
        $terbilang = $this->terbilang($total) . ' RUPIAH';

        return view('invoice', compact('tikets', 'subtotal', 'issued_fee', 'materai', 'total', 'terbilang'));
    }
    private function terbilang($angka)
    {
        $angka = abs($angka);
        $baca = ["", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS"];
        $terbilang = "";

        if ($angka < 12) {
            $terbilang = " " . $baca[$angka];
        } elseif ($angka < 20) {
            $terbilang = $this->terbilang($angka - 10) . " BELAS";
        } elseif ($angka < 100) {
            $terbilang = $this->terbilang(intval($angka / 10)) . " PULUH" . $this->terbilang($angka % 10);
        } elseif ($angka < 200) {
            $terbilang = " Seratus" . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang = $this->terbilang(intval($angka / 100)) . " RATUS" . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $terbilang = " Seribu" . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang = $this->terbilang(intval($angka / 1000)) . " RIBU" . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $terbilang = $this->terbilang(intval($angka / 1000000)) . " JUTA" . $this->terbilang($angka % 1000000);
        }

        return trim($terbilang);
    }
}
