<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Nota;
use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\Biaya;
use App\Models\Insentif;
use App\Models\SubagentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class NotaController extends Controller
{
    public function show($kodeBooking)
    {
        $jenisBayarNonPiutang = JenisBayar::where('id', '!=', 3)->get();

        $nota = Nota::with(['jenisBayar', 'bank', 'tiket', 'jenisBayarNonPiutang'])
            ->where('tiket_kode_booking', $kodeBooking)
            ->firstOrFail();

        return view('nota.detail', compact('nota'));
    }

    public function piutangTiket()
    {
        $jenisBayarNonPiutang = JenisBayar::where('id', '!=', 3)->get();
        $piutang = Nota::with([
                'jenisBayar',
                'bank',
                'tiket.jenisTiket'
            ])
            ->whereHas('jenisBayar', function ($q) {
                $q->where('jenis', 'piutang');
            })
            ->whereNotNull('tiket_kode_booking') // ⬅️ HANYA TIKET
            ->whereNull('tgl_bayar')
            ->orderBy('tgl_issued', 'desc')
            ->get();

        $jenisBayar = JenisBayar::all();
        $bank = Bank::all();

        return view('piutang', compact('piutang', 'jenisBayar', 'bank', 'jenisBayarNonPiutang'));
    }

    public function updatePiutang(Request $request)
    {
        $request->validate([
            'nota_id' => 'required|exists:nota,id',
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id' => 'nullable|exists:bank,id',
            'tgl_bayar' => 'required|date',
        ]);

        $nota = Nota::findOrFail($request->nota_id);

        $nota->update([
            'jenis_bayar_id' => $request->jenis_bayar_id,
            'bank_id' => $request->bank_id,
            'tgl_bayar' => $request->tgl_bayar,
        ]);

        return redirect()->back()->with('success', 'Piutang berhasil direalisasi');
    }

    public function showByKodeBooking($kodeBooking)
    {
        $nota = Nota::where('tiket_kode_booking', $kodeBooking)->first();
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

    public function rekap(Request $request)
    {
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();

        /* PENJUALAN (NON PIUTANG) */
        $TTL_PENJUALAN = DB::table('nota')
            ->whereDate('tgl_bayar', $tanggal)
            ->where('jenis_bayar_id', '!=', '3')
            ->sum('harga_bayar');

        /* PIUTANG */
        $PIUTANG = DB::table('nota')
            ->whereDate('tgl_issued', $tanggal)
            ->where('jenis_bayar_id', '3')
            ->sum('harga_bayar');

        /* PENGELUARAN */
        $BIAYA = DB::table('biaya')
            ->whereDate('tgl', $tanggal)
            ->sum('biaya');

        return view('rekap-penjualan', compact(
            'TTL_PENJUALAN',
            'PIUTANG',
            'BIAYA',
            'tanggal'
        ));
    }
    
    public function rekapPenjualan(Request $request)
    {
        $tanggal = $request->tanggal ?? Carbon::today();

        $TTL_PENJUALAN = Nota::whereDate('tgl_bayar', $tanggal)
            ->whereHas('jenisBayar', fn($q) => $q->where('jenis', '!=', 'piutang'))
            ->sum('harga_bayar');

        $PIUTANG = Nota::whereDate('tgl_bayar', $tanggal)
            ->whereHas('jenisBayar', fn($q) => $q->where('jenis', 'piutang'))
            ->sum('harga_bayar');

        $BIAYA = Biaya::whereDate('tgl', $tanggal)->sum('jumlah');

        return view('rekap-penjualan', compact(
            'TTL_PENJUALAN',
            'PIUTANG',
            'BIAYA'
        ));
    }
}
?>