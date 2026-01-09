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
    public function index(Request $request)
    {
        // Sembunyikan jenis bayar id 3 (piutang) dan 4 (refund) dari input manual
        $jenisBayar = JenisBayar::whereNotIn('id', [3, 4])->get();
        $bank = Bank::all();

        // Dropdown jenis tiket
        $jenisTiket = JenisTiket::orderBy('name_jenis')->get();
        $jenisTiketId = $request->get('jenis_tiket_id', $jenisTiket->first()?->id);


        /* ================= KELUAR (PEMBELIAN TIKET) ================= */
        $nota = DB::table('tiket')
            ->where('jenis_tiket_id', $jenisTiketId)
            ->whereNotNull('tgl_issued')
            ->select(
                'kode_booking as order_id',
                'tgl_issued as tanggal',
                DB::raw('-nta as transaksi'),
                DB::raw("'Pembelian Tiket' as keterangan")
            )
            ->get();


        /* ================= MASUK (TOP UP TIKET) ================= */
        $topupHistories = DB::table('topup_histories')
            ->where('jenis_tiket_id', $jenisTiketId)
            ->select(
                'topup_histories.id as order_id',
                'tgl_issued as tanggal',
                'transaksi',
                DB::raw("COALESCE(keterangan, 'Top Up Tiket') as keterangan")
            )
            ->get();

        /* ================= GABUNG & SORT ASC ================= */
        $mutasi = collect()
            ->merge($nota)
            ->merge($topupHistories)
            ->sortBy([
                ['tanggal', 'asc'],
                ['order_id', 'asc'],
            ])
            ->values();

        /* ================= SALDO BERJALAN (DARI BAWAH KE ATAS) ================= */
        $saldo = 0;

        $mutasi = $mutasi
            ->map(function ($row) use (&$saldo) {
                $saldo += $row->transaksi;
                $row->saldo = $saldo;
                return $row;
            })
            ->values();

        $tiketRefund = Tiket::with('jenisTiket')
            ->where('status', 'issued')
            ->orderBy('tgl_issued', 'desc')
            ->get();

        return view('mutasi', [
            'jenisTiket'   => $jenisTiket,
            'jenisTiketId' => $jenisTiketId,
            'mutasi'       => $mutasi,
            'saldoTiket'   => $saldo,
            'jenisBayar'   => $jenisBayar,
            'bank'         => $bank,
            'tiketRefund'  => $tiketRefund,
        ]);
    }

    /**
     * Form tambah mutasi tiket
     */
    public function create()
    {
        $tikets     = Tiket::orderBy('kode_booking')->get();
        $jenisBayar = JenisBayar::where('id', '!=', 4)->get();
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
        ->route('mutasi-tiket.index', [
            'jenis_tiket_id' => $request->jenis_tiket_id
        ])
        ->with('success', 'Mutasi berhasil disimpan');
}



    /**
     * Form edit mutasi tiket
     */
    public function edit($id)
    {
        $mutasi     = MutasiTiket::findOrFail($id);
        $tikets     = Tiket::orderBy('kode_booking')->get();
        $jenisBayar = JenisBayar::where('id', '!=', 4)->get();
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
            'jenisBayarNonPiutang' => JenisBayar::whereNotIn('id', [3, 4])->get(), // exclude PIUTANG & REFUND
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
                'penjualan' => DB::table('mutasi_tiket as m')
                    ->join('tiket as t', 't.kode_booking', '=', 'm.tiket_kode_booking')
                    ->where('t.jenis_tiket_id', $jt->id)
                    ->whereNotNull('m.tgl_bayar')
                    ->whereBetween(DB::raw('DATE(m.tgl_bayar)'), [$tanggalAwal, $tanggalAkhir])
                    ->sum('m.harga_bayar'),

                'nta' => DB::table('tiket as t')
                    ->join('mutasi_tiket as m', 'm.tiket_kode_booking', '=', 't.kode_booking')
                    ->where('t.jenis_tiket_id', $jt->id)
                    ->whereNotNull('m.tgl_bayar')
                    ->whereBetween(DB::raw('DATE(m.tgl_bayar)'), [$tanggalAwal, $tanggalAkhir])
                    ->sum('t.nta'),
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
        $detailTiket = DB::table('mutasi_tiket')
            ->join('tiket', 'tiket.kode_booking', '=', 'mutasi_tiket.tiket_kode_booking')
            ->join('jenis_tiket', 'jenis_tiket.id', '=', 'tiket.jenis_tiket_id')
            ->leftJoin('jenis_bayar', 'jenis_bayar.id', '=', 'mutasi_tiket.jenis_bayar_id')
            ->whereNotNull('mutasi_tiket.tgl_bayar')
            ->whereBetween(
                DB::raw('DATE(mutasi_tiket.tgl_bayar)'),
                [$tanggalAwal, $tanggalAkhir]
            )
            ->select(
                DB::raw('DATE(mutasi_tiket.tgl_bayar) as tgl_issu'),
                DB::raw('TIME(mutasi_tiket.tgl_bayar) as jam'),
                'tiket.kode_booking',
                'jenis_tiket.name_jenis as jenis_tiket',
                'tiket.name as nama',
                'tiket.rute',
                'tiket.tgl_flight',
                'tiket.rute2',
                'tiket.tgl_flight2',
                'mutasi_tiket.harga_bayar as harga',
                'tiket.nta',
                'jenis_bayar.jenis as pembayaran'
            )
            ->orderBy('mutasi_tiket.tgl_bayar')
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

            /* JENIS BAYAR ID = 2 */
        // Total dari mutasi_tiket dengan jenis_bayar_id = 2
        $jenisBayarCashFlowMutasi = DB::table('mutasi_tiket')
            ->where('jenis_bayar_id', 2)
            ->where('tgl_bayar', $tanggal)
            ->sum('harga_bayar');

        // Total dari ppob_histories dengan jenis_bayar_id = 2
        $jenisBayarCashFlowPpob = DB::table('ppob_histories')
            ->where('jenis_bayar_id', 2)
            ->where('tgl', $tanggal)
            ->sum('harga_jual');

        // Total jenis_bayar_id = 2
        $CASH_FLOW = $jenisBayarCashFlowMutasi + $jenisBayarCashFlowPpob;

        /* PENGELUARAN (HANYA KATEGORI 'lainnya') */
        $BIAYA = DB::table('biaya')
            ->where('kategori', 'lainnya')
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

        /* AMBIL SEMUA JENIS TIKET + TOTAL TOP UP (DARI TOPUP_HISTORIES) */
        $topupJenisTiket = JenisTiket::leftJoin('topup_histories', function ($join) use ($tanggal) {
                $join->on('jenis_tiket.id', '=', 'topup_histories.jenis_tiket_id')
                    ->whereDate('topup_histories.tgl_issued', $tanggal);
            })
            ->select(
                'jenis_tiket.id',
                'jenis_tiket.name_jenis',
                'jenis_tiket.saldo',
                DB::raw('COALESCE(SUM(topup_histories.transaksi), 0) as total_topup')
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
            'CASH_FLOW',
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
        $jenisData    = $request->jenis_data ?? 'tiket';

        /* ================= DATA TIKET ================= */
        if ($jenisData === 'tiket') {

            $data = DB::table('mutasi_tiket')
                ->join('tiket', 'tiket.kode_booking', '=', 'mutasi_tiket.tiket_kode_booking')
                ->join('jenis_tiket', 'jenis_tiket.id', '=', 'tiket.jenis_tiket_id')
                ->leftJoin('jenis_bayar', 'jenis_bayar.id', '=', 'mutasi_tiket.jenis_bayar_id')
                ->leftJoin('bank', 'bank.id', '=', 'mutasi_tiket.bank_id')

                // hanya transaksi yang SUDAH DIBAYAR
                ->whereNotNull('mutasi_tiket.tgl_bayar')
                ->whereBetween(
                    DB::raw('DATE(mutasi_tiket.tgl_bayar)'),
                    [$tanggalAwal, $tanggalAkhir]
                )

                ->orderBy('mutasi_tiket.tgl_bayar')

                ->select([
                    // ===== TANGGAL & JAM =====
                    DB::raw('DATE(mutasi_tiket.tgl_bayar) as tgl_issued'),
                    DB::raw('TIME(mutasi_tiket.tgl_bayar) as jam'),

                    // ===== IDENTITAS =====
                    'tiket.kode_booking',
                    'jenis_tiket.name_jenis as airlines',
                    'tiket.name as nama',

                    // ===== RUTE =====
                    'tiket.rute as rute1',
                    'tiket.tgl_flight as tgl_flight1',
                    'tiket.rute2',
                    'tiket.tgl_flight2',

                    // ===== KEUANGAN =====
                    'mutasi_tiket.harga_bayar as harga',
                    'tiket.nta',
                    DB::raw('0 as diskon'),
                    DB::raw('(mutasi_tiket.harga_bayar - tiket.nta) as komisi'),

                    // ===== PEMBAYARAN =====
                    'jenis_bayar.jenis as pembayaran',
                    'mutasi_tiket.nama_piutang',

                    DB::raw('DATE(mutasi_tiket.tgl_bayar) as tgl_realisasi'),
                    DB::raw('TIME(mutasi_tiket.tgl_bayar) as jam_realisasi'),

                    // ===== LAINNYA =====
                    'tiket.nilai_refund',
                    'mutasi_tiket.keterangan',
                    DB::raw("'-' as usr")
                ])
                ->get();
        }
        

        /* ================= EXPORT CSV ================= */
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($data) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'TGL_ISSUED','JAM','KODE_BOOKING','AIRLINES','NAMA',
                'RUTE1','TGL_FLIGHT1','RUTE2','TGL_FLIGHT2',
                'HARGA','NTA','DISKON','KOMISI','PEMBAYARAN',
                'NAMA_PIUTANG','TGL_REALISASI','JAM_REALISASI',
                'NILAI_REFUND','KETERANGAN','USR'
            ]);

            foreach ($data as $row) {
                fputcsv($handle, [
                    $row->tgl_issued,
                    $row->jam,
                    $row->kode_booking,
                    $row->airlines,
                    $row->nama,
                    $row->rute1,
                    $row->tgl_flight1,
                    $row->rute2,
                    $row->tgl_flight2,
                    $row->harga,
                    $row->nta,
                    $row->diskon,
                    $row->komisi,
                    strtoupper($row->pembayaran ?? '-'),
                    $row->nama_piutang,
                    $row->tgl_realisasi,
                    $row->jam_realisasi,
                    $row->nilai_refund,
                    $row->keterangan,
                    $row->usr
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set(
            'Content-Disposition',
            'attachment; filename="rekap-penjualan.csv"'
        );

        return $response;
    }

}
