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
use Symfony\Component\HttpFoundation\StreamedResponse;



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
        $jenisData = $request->jenis_data ?? 'tiket';

        /* ================= MASTER DATA ================= */
        $jenisTiket = JenisTiket::orderBy('name_jenis')->get();
        $ppobs      = JenisPpob::orderBy('jenis_ppob')->get();

        /* ================= PENJUALAN PER JENIS ================= */
        $penjualan = [];

        // --- JENIS TIKET ---
        foreach ($jenisTiket as $jt) {
            $penjualan['tiket'][$jt->name_jenis] = [
                'penjualan' => DB::table('tiket')
                    ->where('jenis_tiket_id', $jt->id)
                    ->whereBetween('tgl_issued', [$tanggalAwal, $tanggalAkhir])
                    ->sum('harga_jual'),

                'nta' => DB::table('tiket')
                    ->where('jenis_tiket_id', $jt->id)
                    ->whereBetween('tgl_issued', [$tanggalAwal, $tanggalAkhir])
                    ->sum('nta'),
            ];
        }

        // --- JENIS PPOB ---
        foreach ($ppobs as $ppob) {
            $penjualan['ppob'][$ppob->jenis_ppob] = [
                'penjualan' => DB::table('ppob_histories')
                    ->where('jenis_ppob_id', $ppob->id)
                    ->whereBetween('tgl', [$tanggalAwal, $tanggalAkhir])
                    ->sum('harga_jual'),

                'nta' => DB::table('ppob_histories')
                    ->where('jenis_ppob_id', $ppob->id)
                    ->whereBetween('tgl', [$tanggalAwal, $tanggalAkhir])
                    ->sum('nta'),
            ];
        }

        /* ================= TOTAL SUMMARY ================= */
        $TTL_PENJUALAN = collect($penjualan['tiket'])->sum('penjualan')
            + collect($penjualan['ppob'])->sum('penjualan');

        $TTL_NTA = collect($penjualan['tiket'])->sum('nta')
            + collect($penjualan['ppob'])->sum('nta');

        /* ================= DETAIL TABEL (TIKET ONLY) ================= */
        $detailTiket = DB::table('tiket')
            ->join('jenis_tiket', 'jenis_tiket.id', '=', 'tiket.jenis_tiket_id')
            ->leftJoin('mutasi_tiket', 'mutasi_tiket.tiket_kode_booking', '=', 'tiket.kode_booking')
            ->leftJoin('jenis_bayar', 'jenis_bayar.id', '=', 'mutasi_tiket.jenis_bayar_id')
            ->whereBetween('tiket.tgl_issued', [$tanggalAwal, $tanggalAkhir])
            ->select(
                DB::raw('DATE(tiket.tgl_issued) as tgl_issu'),
                DB::raw('TIME(tiket.created_at) as jam'),
                'tiket.kode_booking',
                'jenis_tiket.name_jenis as jenis_tiket',
                'tiket.name as nama',
                'tiket.rute',
                'tiket.tgl_flight',
                'tiket.harga_jual as harga',
                'tiket.nta',
                'jenis_bayar.jenis as pembayaran'
            )
            ->orderBy('tiket.tgl_issued')
            ->get();


        /* --- DATA PPOB --- */
        $ppob = DB::table('ppob_histories')
            ->join('jenis_ppob', 'jenis_ppob.id', '=', 'ppob_histories.jenis_ppob_id')
            ->leftJoin('jenis_bayar', 'jenis_bayar.id', '=', 'ppob_histories.jenis_bayar_id')
            ->whereBetween('ppob_histories.tgl', [$tanggalAwal, $tanggalAkhir])
            ->select(
                'ppob_histories.tgl as tgl_issu',
                DB::raw('TIME(ppob_histories.created_at) as jam'),
                DB::raw("CONCAT('PPOB-', ppob_histories.id) as kode_booking"),
                'jenis_ppob.jenis_ppob as kategori',
                'ppob_histories.harga_jual as harga',
                'ppob_histories.nta',
                'jenis_bayar.jenis as pembayaran'
            )
            ->get();

        $tableData = collect();
        if ($jenisData === 'ppob') {
            $tableData = $ppob;
        } else {
            $tableData = $detailTiket;
        }


        return view('rekap-penjualan', compact(
            'tanggalAwal',
            'tanggalAkhir',
            'jenisTiket',
            'ppobs',
            'penjualan',
            'TTL_PENJUALAN',
            'TTL_NTA',
            'tableData',
            'jenisData'
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

    public function exportRekapPenjualan(Request $request)
    {
        $tanggalAwal  = $request->tanggal_awal ?? now()->toDateString();
        $tanggalAkhir = $request->tanggal_akhir ?? now()->toDateString();

        $data = DB::table('mutasi_tiket')
            ->join('tikets', 'tikets.id', '=', 'mutasi_tiket.tiket_id')
            ->leftJoin('jenis_bayar', 'jenis_bayar.id', '=', 'mutasi_tiket.jenis_bayar_id')
            ->leftJoin('bank', 'bank.id', '=', 'mutasi_tiket.bank_id')
            ->whereBetween('mutasi_tiket.tgl_issued', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('mutasi_tiket.tgl_issued')
            ->select(
                'mutasi_tiket.tgl_issued',
                'mutasi_tiket.tgl_bayar',
                'tikets.kode_booking',
                'tikets.airlines',
                'tikets.nama',
                'tikets.rute1',
                'tikets.tgl_flight1',
                'mutasi_tiket.harga_bayar',
                'mutasi_tiket.insentif',
                'jenis_bayar.jenis as jenis_bayar',
                'bank.name as bank'
            )
            ->get();

        $response = new StreamedResponse(function () use ($data) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'TGL ISSU',
                'TGL BAYAR',
                'KODE BOOKING',
                'AIRLINES',
                'NAMA',
                'RUTE',
                'TGL FLIGHT',
                'HARGA',
                'INSENTIF',
                'JENIS BAYAR',
                'BANK'
            ]);

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->tgl_issued,
                    $row->tgl_bayar,
                    $row->kode_booking,
                    $row->airlines,
                    $row->nama,
                    $row->rute1,
                    $row->tgl_flight1,
                    $row->harga_bayar,
                    $row->insentif,
                    strtoupper($row->jenis_bayar ?? '-'),
                    $row->bank ?? '-',
                ]);
            }

            fclose($handle);
        });

        // ✅ SET HEADER DENGAN CARA YANG BENAR
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="rekap-penjualan.csv"'
        );

        return $response;
    }
}
