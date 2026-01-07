<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    // public function show($kode_booking)
    // {
    //     $tiket = Tiket::with([
    //         'jenisTiket',
    //         'nota.jenisBayar',
    //         'nota.bank'
    //     ])
    //     ->where('kode_booking', $kode_booking)
    //     ->firstOrFail();

    //     return view('invoice', compact('tiket'));
    // }

    public function show($kode_booking)
    {
        $tiket = DB::table('tiket')
            ->leftJoin('jenis_tiket', 'jenis_tiket.id', '=', 'tiket.jenis_tiket_id')
            ->select(
                'tiket.*',
                'jenis_tiket.name_jenis as jenis_tiket_name'
            )
            ->where('tiket.kode_booking', $kode_booking)
            ->first();

        if (!$tiket) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $tikets = collect([$tiket]);

        $subtotal = $tiket->harga_jual ?? 0;

        $issued_fee = 25000;
        $materai = 10000;
        $total = $subtotal + $issued_fee + $materai;

        $terbilang = $this->terbilang($total) . ' Rupiah';

        $invoiceId = $tiket->kode_booking;
        $invoice_number = 'INV-' . str_pad(preg_replace('/\D/', '', $invoiceId), 5, '0', STR_PAD_LEFT);

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


    public function showSingle($id)
    {
        $tiket = DB::table('tiket')->where('kode_boooking', $id)->first();

        if (!$tiket) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        $tikets = collect([$tiket]);

        $subtotal = $tiket->harga ?? 0;
        $issued_fee = 25000;
        $materai = 10000;
        $total = $subtotal + $issued_fee + $materai;

        $terbilang = $this->terbilang($total) . ' Rupiah';

        $invoiceId = $tiket->kode_booking;
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
    $codes = $request->query('codes');

    if (!$codes) {
        return redirect()->back()->with('error', 'Pilih minimal satu tiket!');
    }

    $kodeBookingArray = explode(',', $codes);

    $tikets = DB::table('tiket')
        ->leftJoin('jenis_tiket', 'jenis_tiket.id', '=', 'tiket.jenis_tiket_id')
        ->select(
            'tiket.*',
            'jenis_tiket.name_jenis as jenis_tiket_name'
        )
        ->whereIn('tiket.kode_booking', $kodeBookingArray)
        ->get();

    if ($tikets->isEmpty()) {
        return redirect()->back()->with('error', 'Data tiket tidak ditemukan!');
    }

    $subtotal   = $tikets->sum('harga_jual');
    $issued_fee = 25000;
    $materai    = 10000;
    $total      = $subtotal + $issued_fee + $materai;

    $terbilang = $this->terbilang($total) . ' RUPIAH';

    $invoice_number = 'INV-' . date('Ymd-His');

    return view('invoice', compact(
        'tikets',
        'subtotal',
        'issued_fee',
        'materai',
        'total',
        'terbilang',
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
        $tiket = DB::table('tiket')->where('id', $id)->first();

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
