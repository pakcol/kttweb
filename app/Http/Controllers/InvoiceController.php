<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function showSingle($id)
    {
        $tiket = DB::table('ticket')->where('id', $id)->first();

        if (!$tiket) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $tikets = collect([$tiket]);

        $subtotal = $tiket->harga ?? 0;
        $issued_fee = 25000;
        $materai = 10000;
        $total = $subtotal + $issued_fee + $materai;

        $terbilang = $this->terbilang($total) . ' Rupiah';

        $invoiceId = $tiket->id;
        $invoice_number = 'INV-' . str_pad($invoiceId, 5, '0', STR_PAD_LEFT);

        return view('invoice', compact(
            'tikets',
            'subtotal',
            'issued_fee',
            'materai',
            'total',
            'terbilang',
            'invoiceId',
            'invoice_number'
        ));
    }

    public function showMulti(Request $request)
    {
        $ids = $request->query('ids');

        if (!$ids) {
            return redirect()->back()->with('error', 'Silakan pilih minimal satu tiket untuk cetak invoice!');
        }

        $idsArray = array_map('intval', explode(',', $ids));
        $tikets = DB::table('ticket')->whereIn('id', $idsArray)->get();

        if ($tikets->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $subtotal = $tikets->sum('harga');
        $issued_fee = 25000;
        $materai = 10000;
        $total = $subtotal + $issued_fee + $materai;
        $terbilang = $this->terbilang($total) . ' Rupiah';

        $invoiceId = $tikets->first()->id;
        $invoice_number = 'INV-' . date('Ymd') . '-' . str_pad($invoiceId, 5, '0', STR_PAD_LEFT);

        return view('invoice', compact(
            'tikets',
            'subtotal',
            'issued_fee',
            'materai',
            'total',
            'terbilang',
            'invoiceId',
            'invoice_number'
        ));
    }

    // 🆕 Tambahkan fungsi ini di bawah showMulti()
    public function updateMaterai(Request $request, $id)
    {
        $request->validate([
            'materai' => 'required|numeric',
        ]);

        // Ambil tiket berdasarkan ID (karena belum ada tabel invoices)
        $tiket = DB::table('ticket')->where('id', $id)->first();

        if (!$tiket) {
            return redirect()->back()->with('error', 'Data tiket tidak ditemukan!');
        }

        $subtotal = $tiket->harga ?? 0;
        $issued_fee = 25000;
        $materai = $request->materai;
        $total = $subtotal + $issued_fee + $materai;

        // Tidak perlu simpan ke DB kalau cuma tampil di halaman
        // Tapi jika ingin disimpan, tambahkan kolom materai & total di tabel tikets

        return back()->with([
            'materai' => $materai,
            'total' => $total,
        ]);
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
            $terbilang = " SERATUS" . $this->terbilang($angka - 100);
        } elseif ($angka < 1000) {
            $terbilang = $this->terbilang(intval($angka / 100)) . " RATUS" . $this->terbilang($angka % 100);
        } elseif ($angka < 2000) {
            $terbilang = " SERIBU" . $this->terbilang($angka - 1000);
        } elseif ($angka < 1000000) {
            $terbilang = $this->terbilang(intval($angka / 1000)) . " RIBU" . $this->terbilang($angka % 1000);
        } elseif ($angka < 1000000000) {
            $terbilang = $this->terbilang(intval($angka / 1000000)) . " JUTA" . $this->terbilang($angka % 1000000);
        }

        return trim($terbilang);
    }
}
