<?php

namespace App\Http\Controllers;

use Carbon\Carbon;  
use App\Models\MutasiTiket;
use App\Models\Tiket;
use App\Models\JenisBayar;
use App\Models\JenisPpob;
use App\Models\JenisTiket;
use App\Models\Bank;
use App\Models\Biaya;
use App\Models\Subagent;
use App\Services\MutasiTiketService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MutasiTiketController extends Controller
{
    /**
     * Tampilkan daftar mutasi tiket
     */
    public function index()
    {
        $mutasi = MutasiTiket::with([
            'tiket',
            'jenisBayar',
            'bank'
        ])->orderBy('tgl_issued', 'desc')->get();

        return view('mutasi-tiket.index', compact('mutasi'));
    }

    /**
     * Form tambah mutasi tiket
     */
    public function create()
    {
        $tikets     = Tiket::orderBy('kode_booking')->get();
        $jenisBayar = JenisBayar::all();
        $banks      = Bank::all();

        return view('mutasi-tiket.create', compact(
            'tikets',
            'jenisBayar',
            'banks'
        ));
    }

    /**
     * Simpan data mutasi tiket
     */
    public function store(Request $request, MutasiTiketService $service)
    {
        $service->create($request->all());

        return redirect()
            ->route('mutasi-tiket.index')
            ->with('success', 'Mutasi tiket berhasil ditambahkan');
    }

    /**
     * Form edit mutasi tiket
     */
    public function edit($id)
    {
        $mutasi     = MutasiTiket::findOrFail($id);
        $tikets     = Tiket::orderBy('kode_booking')->get();
        $jenisBayar = JenisBayar::all();
        $banks      = Bank::all();

        return view('mutasi-tiket.edit', compact(
            'mutasi',
            'tikets',
            'jenisBayar',
            'banks'
        ));
    }

    /**
     * Update data mutasi tiket
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tiket_id'       => 'required|exists:tikets,id',
            'tgl_issued'     => 'required|date',
            'tgl_bayar'      => 'nullable|date',
            'harga_bayar'    => 'required|numeric|min:0',
            'insentif'       => 'nullable|numeric|min:0',
            'jenis_bayar_id' => 'nullable|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|exists:bank,id',
            'keterangan'     => 'nullable|string',
        ]);

        $mutasi = MutasiTiket::findOrFail($id);

        $mutasi->update([
            'tiket_id'       => $request->tiket_id,
            'tgl_issued'     => $request->tgl_issued,
            'tgl_bayar'      => $request->tgl_bayar,
            'harga_bayar'    => $request->harga_bayar,
            'insentif'       => $request->insentif ?? 0,
            'jenis_bayar_id' => $request->jenis_bayar_id,
            'bank_id'        => $request->bank_id,
            'keterangan'     => $request->keterangan,
        ]);

        return redirect()
            ->route('mutasi-tiket.index')
            ->with('success', 'Mutasi tiket berhasil diperbarui');
    }

    public function indexPiutang()
    {
        // Ambil piutang tiket (belum dibayar)
        $piutang = MutasiTiket::with([
                'tiket.jenisTiket'
            ])
            ->whereNull('tgl_bayar')
            ->orderBy('tgl_issued', 'asc')
            ->get();

        return view('piutang', [
            'piutang' => $piutang,
            'jenisBayarNonPiutang' => JenisBayar::where('id', '!=', 3)->get(), // exclude PIUTANG
            'bank' => Bank::orderBy('name')->get(),
        ]);
    }

    public function updatePiutang(Request $request, $id)
    {
        $validated = $request->validate([
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|exists:bank,id',
            'tgl_bayar'      => 'required|date',
        ]);

        $mutasi = MutasiTiket::findOrFail($id);

        $mutasi->update([
            'jenis_bayar_id' => $validated['jenis_bayar_id'],
            'bank_id'        => $validated['bank_id'] ?? null,
            'tgl_bayar'      => $validated['tgl_bayar'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'Piutang tiket berhasil direalisasikan');
    }


    /**
     * Hapus mutasi tiket
     */
    public function destroy($id)
    {
        MutasiTiket::findOrFail($id)->delete();

        return redirect()
            ->route('mutasi-tiket.index')
            ->with('success', 'Mutasi tiket berhasil dihapus');
    }

    public function rekapPenjualan(Request $request)
    {
       $tanggalAwal  = $request->tanggal_awal ?? now()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->toDateString();

        $banks      = Bank::all();
        $ppobs      = JenisPpob::orderBy('jenis_ppob')->get();
        $jenisTiket = JenisTiket::orderBy('name_jenis')->get();
        $subagents  = Subagent::orderBy('nama')->get();

        /* ================= PENJUALAN ================= */
        // Total dari mutasi_tiket
        $penjualanMutasi = DB::table('mutasi_tiket')
            ->whereBetween('tgl_bayar', [$tanggalAwal, $tanggalAkhir])
            ->sum('harga_bayar');

        // Total dari ppob_histories
        $penjualanPpob = DB::table('ppob_histories')
            ->whereBetween('tgl', [$tanggalAwal, $tanggalAkhir])
            ->sum('harga_jual');

        // Total Penjualan
        $TTL_PENJUALAN = $penjualanMutasi + $penjualanPpob;

        /* ================= PIUTANG ================= */
        // Piutang dari mutasi_tiket
        $piutangMutasi = DB::table('mutasi_tiket')
            ->join('jenis_bayar', 'jenis_bayar.id', '=', 'mutasi_tiket.jenis_bayar_id')
            ->where('jenis_bayar.jenis', 'piutang')
            ->whereBetween('mutasi_tiket.tgl_issued', [$tanggalAwal, $tanggalAkhir])
            ->sum('mutasi_tiket.harga_bayar');

        // Piutang dari ppob_histories
        $piutangPpob = DB::table('ppob_histories')
            ->join('jenis_bayar', 'jenis_bayar.id', '=', 'ppob_histories.jenis_bayar_id')
            ->where('jenis_bayar.jenis', 'piutang')
            ->whereBetween('ppob_histories.tgl', [$tanggalAwal, $tanggalAkhir])
            ->sum('ppob_histories.harga_jual');

        // Total Piutang
        $PIUTANG = $piutangMutasi + $piutangPpob;

        /* ================= BIAYA ================= */
        $BIAYA = DB::table('biaya')
            ->whereBetween('tgl', [$tanggalAwal, $tanggalAkhir])
            ->sum('biaya');

        /* ================= TRANSFER PER BANK ================= */
        $transfer = [];
        foreach ($banks as $bank) {
            $tiketMasuk = MutasiTiket::whereBetween('tgl_bayar', [$tanggalAwal, $tanggalAkhir])
                ->where('bank_id', $bank->id)
                ->whereHas('jenisBayar', fn ($q) => $q->where('jenis', 'bank'))
                ->sum('harga_bayar');

            $biayaKeluar = Biaya::whereBetween('tgl', [$tanggalAwal, $tanggalAkhir])
                ->where('bank_id', $bank->id)
                ->where('kategori', 'lainnya')
                ->sum('biaya');

            $transfer[$bank->id] = $tiketMasuk - $biayaKeluar;
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
            ->sum('transaksi');

        return view('rekap-penjualan', compact(
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

    public function cashFlow(Request $request)
    {
        $tanggal = $request->tanggal ?? Carbon::today()->toDateString();
        $banks = Bank::all();
        $ppobs = JenisPpob::orderBy('jenis_ppob')->get();
        $jenisTiket = JenisTiket::orderBy('name_jenis')->get();
        $subagents = Subagent::orderBy('nama')->get();
        $transfer = [];

        /* PENJUALAN (NON PIUTANG) */
        // Total dari mutasi_tiket
        $penjualanMutasi = DB::table('mutasi_tiket')
            ->where('tgl_bayar', $tanggal)
            ->sum('harga_bayar');

         // Total dari ppob_histories
        $penjualanPpob = DB::table('ppob_histories')
            ->where('tgl', $tanggal)
            ->sum('harga_jual');

        // Total Penjualan
        $TTL_PENJUALAN = $penjualanMutasi + $penjualanPpob;

        /* PIUTANG */
        // Piutang dari mutasi_tiket
        $piutangMutasi = DB::table('mutasi_tiket')
            ->join('jenis_bayar', 'jenis_bayar.id', '=', 'mutasi_tiket.jenis_bayar_id')
            ->where('jenis_bayar.jenis', 'piutang')
            ->where('mutasi_tiket.tgl_issued', $tanggal)
            ->sum('mutasi_tiket.harga_bayar');

        // Piutang dari ppob_histories
        $piutangPpob = DB::table('ppob_histories')
            ->join('jenis_bayar', 'jenis_bayar.id', '=', 'ppob_histories.jenis_bayar_id')
            ->where('jenis_bayar.jenis', 'piutang')
            ->where('ppob_histories.tgl', $tanggal)
            ->sum('ppob_histories.harga_jual');

        // Total Piutang
        $PIUTANG = $piutangMutasi + $piutangPpob;

        /* PENGELUARAN */
        $BIAYA = DB::table('biaya')
            ->whereDate('tgl', $tanggal)
            ->sum('biaya');

        /* ================= TRANSFER PER BANK ================= */
        foreach ($banks as $bank) {
            // TOTAL MASUK DARI NOTA (TRANSFER)
            $tiketMasuk = MutasiTiket::where('tgl_bayar', $tanggal)
                ->where('bank_id', $bank->id)
                ->whereHas('jenisBayar', fn ($q) => $q->where('jenis', 'bank'))
                ->sum('harga_bayar');

            $biayaKeluar = Biaya::where('tgl', $tanggal)
                ->where('bank_id', $bank->id)
                ->where('kategori', 'lainnya')
                ->sum('biaya');

            $transfer[$bank->id] = $tiketMasuk - $biayaKeluar;
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
            ->sum('transaksi');


        return view('cash-flow', compact(
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
}
