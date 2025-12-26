<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class NotaController extends Controller
{
    public function show($kodeBooking)
    {
        $nota = Nota::with(['jenisBayar', 'bank', 'tiket'])
            ->where('tiket_kode_booking', $kodeBooking)
            ->firstOrFail();

        return view('nota.detail', compact('nota'));
    }

    public function showByKodeBooking($kodeBooking)
    {
        $nota = Nota::with(['jenisBayar', 'bank'])
            ->where('tiket_kode_booking', $kodeBooking)
            ->first();

        if (!$nota) {
            return response()->json([
                'jenis_bayar_id' => null,
                'bank_id' => null,
                'nama_piutang' => null,
                'tgl_realisasi' => null,
                'jam_realisasi' => null,
                'nilai_refund' => null,
            ]);
        }

        return response()->json([
            'jenis_bayar_id' => $nota->jenis_bayar_id,
            'bank_id'        => $nota->bank_id,
            'nama_piutang'   => $nota->nama_piutang,
            'tgl_realisasi'  => $nota->tgl_realisasi,
            'jam_realisasi'  => $nota->jam_realisasi,
            'nilai_refund'   => $nota->nilai_refund,
        ]);
    }
}

?>