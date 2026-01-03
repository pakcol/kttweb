<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Nota;
use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\Biaya;
use App\Models\JenisTiket;
use App\Models\JenisPpob;
use App\Models\Insentif;
use App\Models\Subagent;
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
        $banks = Bank::all();
        $ppobs = JenisPpob::orderBy('jenis_ppob')->get();
        $jenisTiket = JenisTiket::orderBy('name_jenis')->get();
        $subagents = Subagent::orderBy('nama')->get();
        $transfer = [];

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

        foreach ($banks as $bank) {
            // TOTAL MASUK DARI NOTA (TRANSFER)
            $notaMasuk = Nota::whereDate('tgl_bayar', $tanggal)
                ->where('bank_id', $bank->id)
                ->whereHas('jenisBayar', function ($q) {
                    $q->where('jenis', 'bank');
                })
                ->sum('harga_bayar');

            // TOTAL KELUAR DARI BIAYA (LAINNYA)
            $biayaKeluar = Biaya::whereDate('tgl', $tanggal)
                ->where('bank_id', $bank->id)
                ->where('kategori', 'lainnya')
                ->sum('biaya');

            // NET TRANSFER PER BANK
            $transfer[$bank->id] = $notaMasuk - $biayaKeluar;
        }

        /* AMBIL SEMUA JENIS TIKET + TOTAL TOP UP */
        $topupJenisTiket = JenisTiket::leftJoin('biaya', function ($join) use ($tanggal) {
                $join->on('jenis_tiket.id', '=', 'biaya.id_jenis_tiket')
                    ->where('biaya.kategori', 'top_up')
                    ->whereDate('biaya.tgl', $tanggal);
            })
            ->select(
                'jenis_tiket.id',
                'jenis_tiket.name_jenis',
                'jenis_tiket.saldo',
                DB::raw('COALESCE(SUM(biaya.biaya), 0) as total_topup')
            )
            ->groupBy('jenis_tiket.id', 'jenis_tiket.name_jenis', 'jenis_tiket.saldo')
            ->orderBy('jenis_tiket.name_jenis')
            ->get();

        $cashFlowSubagent = DB::table('subagent_histories')
            ->whereDate('tgl_issued', $tanggal)
            ->sum('harga_jual');


        return view('rekap-penjualan', compact(
            'TTL_PENJUALAN',
            'PIUTANG',
            'BIAYA',
            'tanggal',
            'banks',
            'transfer',
            'jenisTiket',
            'topupJenisTiket',
            'subagents',
            'cashFlowSubagent',
            'ppobs'
        ));
    }

    public function cashFlow(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal ?? now()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->toDateString();

        $banks      = Bank::all();
        $ppobs      = JenisPpob::orderBy('jenis_ppob')->get();
        $jenisTiket = JenisTiket::orderBy('name_jenis')->get();
        $subagents  = Subagent::orderBy('nama')->get();

        /* ================= PENJUALAN ================= */
        $TTL_PENJUALAN = DB::table('nota')
            ->whereBetween('tgl_bayar', [$tanggalAwal, $tanggalAkhir])
            ->where('jenis_bayar_id', '!=', 3)
            ->sum('harga_bayar');

        /* ================= PIUTANG ================= */
        $PIUTANG = DB::table('nota')
            ->whereBetween('tgl_issued', [$tanggalAwal, $tanggalAkhir])
            ->where('jenis_bayar_id', 3)
            ->sum('harga_bayar');

        /* ================= BIAYA ================= */
        $BIAYA = DB::table('biaya')
            ->whereBetween('tgl', [$tanggalAwal, $tanggalAkhir])
            ->sum('biaya');

        /* ================= TRANSFER PER BANK ================= */
        $transfer = [];
        foreach ($banks as $bank) {
            $notaMasuk = Nota::whereBetween('tgl_bayar', [$tanggalAwal, $tanggalAkhir])
                ->where('bank_id', $bank->id)
                ->whereHas('jenisBayar', fn ($q) => $q->where('jenis', 'bank'))
                ->sum('harga_bayar');

            $biayaKeluar = Biaya::whereBetween('tgl', [$tanggalAwal, $tanggalAkhir])
                ->where('bank_id', $bank->id)
                ->where('kategori', 'lainnya')
                ->sum('biaya');

            $transfer[$bank->id] = $notaMasuk - $biayaKeluar;
        }

        /* ================= TOP UP TIKET ================= */
        $topupJenisTiket = JenisTiket::leftJoin('biaya', function ($join) use ($tanggalAwal, $tanggalAkhir) {
                $join->on('jenis_tiket.id', '=', 'biaya.id_jenis_tiket')
                    ->where('biaya.kategori', 'top_up')
                    ->whereBetween('biaya.tgl', [$tanggalAwal, $tanggalAkhir]);
            })
            ->select(
                'jenis_tiket.id',
                'jenis_tiket.name_jenis',
                'jenis_tiket.saldo',
                DB::raw('COALESCE(SUM(biaya.biaya), 0) as total_topup')
            )
            ->groupBy('jenis_tiket.id', 'jenis_tiket.name_jenis', 'jenis_tiket.saldo')
            ->orderBy('jenis_tiket.name_jenis')
            ->get();

        /* ================= SUBAGENT CASH FLOW ================= */
        $cashFlowSubagent = DB::table('subagent_histories')
            ->whereBetween('tgl_issued', [$tanggalAwal, $tanggalAkhir])
            ->sum('harga_jual');

        return view('cash-flow', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'TTL_PENJUALAN',
            'PIUTANG',
            'BIAYA',
            'banks',
            'transfer',
            'jenisTiket',
            'topupJenisTiket',
            'subagents',
            'cashFlowSubagent',
            'ppobs'
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