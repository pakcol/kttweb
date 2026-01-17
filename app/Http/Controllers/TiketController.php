<?php

namespace App\Http\Controllers;

use App\Services\MutasiTiketService;
use App\Models\Tiket;
use App\Models\JenisTiket;
use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\MutasiTiket;
use App\Models\MutasiBank;
use App\Models\Subagent;
use App\Models\Piutang;
use App\Models\TopupHistory;
use App\Models\SubagentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TiketController extends Controller
{
    /**
     * Menampilkan semua tiket untuk halaman input-tiket
     */
    public function index()
    {
        return view('input-tiket', [
            'subagents'   => Subagent::all(),
            'ticket' => Tiket::with([
                'jenisTiket',
                'mutasiTiket.jenisBayar',
                'mutasiTiket.bank'
            ])->latest()->get(),
            'jenisBayar'  => JenisBayar::where('id', '!=', 4)->get(),
            'jenisBayarNonPiutang' => JenisBayar::whereNotIn('id', [3, 4])->get(),
            'bank'        => Bank::all(),
            'jenisTiket'  => JenisTiket::all(),
        ]);
    }

    public function indexPiutang()
    {
        $piutang = MutasiTiket::with([
                'tiket',
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

    public function indexFind()
    {
        return view('find', [
            'tiket'      => Tiket::with(['jenisTiket'])->latest()->get(),
            'jenisBayar'  => JenisBayar::where('id', '!=', 4)->get(),
            'jenisTiket'  => JenisTiket::all(),
            'bank'        => Bank::all(),
        ]);
    }

    public function searchTiket(Request $request)
    {
        $query = Tiket::with('jenisTiket');
    if ($request->filled('kode_booking')) {
        $query->where('kode_booking', 'LIKE', '%' . $request->kode_booking . '%');
    }

    if ($request->filled('nama_pax')) {
        $query->where('name', 'LIKE', '%' . $request->nama_pax . '%');
    }

    if ($request->filled('tgl_flight')) {
        $query->whereDate('tgl_flight', $request->tgl_flight);
    }

    if ($request->filled('tgl_issued')) {
        $query->whereDate('tgl_issued', $request->tgl_issued);
    }

    if ($request->filled('tgl_realisasi')) {
        $query->whereDate('tgl_realisasi', $request->tgl_realisasi);
    }

    if ($request->filled('nama_piutang')) {
        $query->whereHas('mutasiTiket', function ($q) use ($request) {
            $q->where('nama_piutang', 'LIKE', '%' . $request->nama_piutang . '%');
        });
    }

    $ticket = $query
        ->orderBy('tgl_issued', 'desc')
        ->get();

    return view('find', compact('ticket'));
    }
    
    public function topupMutasi(Request $request)
    {
        $isRefund = $request->boolean('is_refund');

        // ================= VALIDASI =================
        if ($isRefund) {
            $request->validate([
                'tanggal'        => 'required|date',
                'kode_booking'   => 'required|exists:tiket,kode_booking',
                'jenis_tiket_id' => 'required|exists:jenis_tiket,id',
            ]);
        } else {
            $request->validate([
                'tanggal'        => 'required|date',
                'topup'          => 'required|numeric|min:1',
                'jenis_tiket_id' => 'required|exists:jenis_tiket,id',
                'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
                'bank_id'        => 'nullable|exists:bank,id',
                'keterangan'     => 'nullable|string|max:30',
            ]);
        }

        DB::beginTransaction();

        try {

            /* =====================================================
            🔁 MODE REFUND TIKET
            ===================================================== */
            if ($isRefund) {

                $tiket = Tiket::with('jenisTiket')
                    ->where('kode_booking', $request->kode_booking)
                    ->where('status', 'issued')
                    ->firstOrFail();

                $nominalRefund = $tiket->harga_jual;

                // 1️⃣ TAMBAH SALDO JENIS TIKET
                $jenisTiket = JenisTiket::findOrFail($tiket->jenis_tiket_id);
                $jenisTiket->increment('saldo', $nominalRefund);

                // 2️⃣ SIMPAN MUTASI TOPUP HISTORY (REFUND = PLUS)
                TopupHistory::create([
                    'tgl_issued'      => $request->tanggal,
                    'transaksi'       => $nominalRefund,
                    'jenis_tiket_id'  => $tiket->jenis_tiket_id,
                    'subagent_id'     => null,
                    'jenis_bayar_id'  => 4, // REFUND
                    'bank_id'         => null,
                    'keterangan'      => 'Refund Tiket '
                        . $tiket->jenisTiket->name_jenis
                        . ' '
                        . $tiket->kode_booking,
                ]);

                // 3️⃣ UPDATE STATUS TIKET
                $tiket->update([
                    'status'        => 'refunded',
                    'nilai_refund'  => $nominalRefund,
                    'tgl_realisasi' => $request->tanggal,
                ]);
            }

            /* =====================================================
            ➕ MODE TOP UP NORMAL
            ===================================================== */
            else {

                // 1️⃣ TAMBAH SALDO JENIS TIKET
                $jenisTiket = JenisTiket::lockForUpdate()->findOrFail($request->jenis_tiket_id);
                $jenisTiket->increment('saldo', $request->topup);

                // 2️⃣ KURANGI SALDO JENIS BAYAR (CASH / BANK / DLL)
                $jenisBayar = JenisBayar::lockForUpdate()->findOrFail($request->jenis_bayar_id);
                if ($jenisBayar->saldo < $request->topup) {
                    throw new \Exception('Saldo ' . $jenisBayar->jenis . ' tidak mencukupi untuk top up.');
                }
                $jenisBayar->decrement('saldo', $request->topup);

                // 3️⃣ JIKA BANK → KURANGI SALDO BANK
                $bankId = $request->jenis_bayar_id == 1 ? $request->bank_id : null;
                if ($bankId) {
                    $bank = Bank::lockForUpdate()->findOrFail($bankId);
                    if ($bank->saldo < $request->topup) {
                        throw new \Exception('Saldo bank ' . $bank->name . ' tidak mencukupi untuk top up.');
                    }
                    // catat mutasi BANK: uang keluar untuk top up jenis_tiket
                    $saldoSesudah = $bank->saldo - $request->topup;
                    MutasiBank::create([
                        'bank_id'    => $bank->id,
                        'tanggal'    => now(),
                        'ref_type'   => 'TOPUP_JENIS_TIKET',
                        'ref_id'     => $request->jenis_tiket_id,
                        'debit'      => 0,
                        'kredit'     => $request->topup,
                        'saldo'      => $saldoSesudah,
                        'keterangan' => $request->keterangan ?? 'Top up jenis tiket',
                    ]);
                    $bank->decrement('saldo', $request->topup);
                }

                // 4️⃣ SIMPAN MUTASI TOPUP HISTORY
                TopupHistory::create([
                    'tgl_issued'      => $request->tanggal,
                    'transaksi'       => $request->topup,
                    'jenis_tiket_id'  => $request->jenis_tiket_id,
                    'subagent_id'     => null,
                    'jenis_bayar_id'  => $request->jenis_bayar_id,
                    'bank_id'         => $bankId,
                    'keterangan'      => $request->keterangan,
                ]);
            }

            DB::commit();
            return redirect()
                ->route('mutasi-tiket.index', [
                    'jenis_tiket_id' => $request->jenis_tiket_id
                ])
                ->with('success', 'Mutasi berhasil disimpan');


        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menyimpan tiket baru atau update tiket yang ada, plus simpan ke nota
     */
    public function store(Request $request, MutasiTiketService $mutasiService)
    {
        // VALIDASI UMUM
        $request->validate([
            'statusCustomer' => 'required|in:customer,subagent',

            'tgl_issued'     => 'required|date',
            'kode_booking'   => 'required|string|max:10|unique:tiket,kode_booking',
            'name'           => 'required|string|max:100',
            'harga_jual'     => 'required|integer|min:0',
            'nta'            => 'required|integer|min:0',
            'diskon'         => 'required|integer|min:0',
            'komisi'         => 'required|integer|min:0',
            'rute'           => 'required|string|max:45',
            'tgl_flight'     => 'required|date',
            'rute2'          => 'nullable|string|max:45',
            'tgl_flight2'    => 'nullable|date',
            'jenis_tiket_id' => 'required|exists:jenis_tiket,id',
            'keterangan'     => 'nullable|string|max:200',
        ]);

        DB::transaction(function () use ($request, $mutasiService) {

            // SUBAGENT
            if ($request->statusCustomer === 'subagent') {

                $request->validate([
                    'subagent_id' => 'required|exists:subagents,id',
                ]);

                $subagent = Subagent::lockForUpdate()
                    ->findOrFail($request->subagent_id);

                if ($subagent->saldo < $request->nta) {
                    throw new \Exception('Saldo subagent tidak cukup');
                }

                // potong saldo subagent
                $subagent->decrement('saldo', $request->nta);

                // create tiket
                $tiket = Tiket::create([
                    'kode_booking'   => strtoupper($request->kode_booking),
                    'tgl_issued'     => $request->tgl_issued,
                    'name'           => strtoupper($request->name),
                    'harga_jual'     => $request->harga_jual,
                    'nta'            => $request->nta,
                    'diskon'         => $request->diskon,
                    'komisi'         => $request->komisi,
                    'rute'           => strtoupper($request->rute),
                    'tgl_flight'     => $request->tgl_flight,
                    'rute2'          => strtoupper($request->rute2),
                    'tgl_flight2'    => $request->tgl_flight2,
                    'status'         => 'issued',
                    'jenis_tiket_id' => $request->jenis_tiket_id,
                    'keterangan'     => strtoupper($request->keterangan),
                ]);

                // history subagent
                SubagentHistory::create([
                    'tgl_issued'   => $tiket->tgl_issued,
                    'subagent_id'  => $subagent->id,
                    'kode_booking' => $tiket->kode_booking,
                    'status'       => 'pesan_tiket',
                    'transaksi'    => -$tiket->harga_jual,
                ]);

                // $mutasiService->create([
                //     'tiket_kode_booking' => $tiket->kode_booking,
                //     'tgl_issued'         => $tiket->tgl_issued,
                //     'tgl_bayar'          => $tiket->tgl_issued,
                //     'harga_bayar'        => $tiket->nta * -1, 
                //     'jenis_bayar_id'     => null,
                //     'bank_id'            => null,
                //     'keterangan'         => 'PEMESANAN TIKET SUBAGENT',
                // ]);


                return;
            }

            // CUSTOMER BIASA
            $request->validate([
                'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
                'bank_id'        => 'nullable|exists:bank,id',
                'nama_piutang'   => 'nullable|string|max:100',
            ]);

            $tiket = Tiket::create([
                'kode_booking'   => strtoupper($request->kode_booking),
                'tgl_issued'     => $request->tgl_issued,
                'name'           => strtoupper($request->name),
                'harga_jual'     => $request->harga_jual,
                'nta'            => $request->nta,
                'diskon'         => $request->diskon,
                'komisi'         => $request->harga_jual - $request->diskon - $request->nta,
                'rute'           => strtoupper($request->rute),
                'tgl_flight'     => $request->tgl_flight,
                'rute2'          => strtoupper($request->rute2),
                'tgl_flight2'    => $request->tgl_flight2,
                'status'         => 'issued',
                'jenis_tiket_id' => $request->jenis_tiket_id,
                'keterangan'     => strtoupper($request->keterangan),
            ]);

            if ((int)$request->jenis_bayar_id === 3) {

                $piutang = Piutang::firstOrCreate(
                    ['nama' => strtoupper($request->nama_piutang)],
                    ['jumlah' => 0]
                );

                // tambah total piutang
                $piutang->increment('jumlah', $tiket->harga_jual);

                // simpan mutasi
                $mutasiService->create([
                    'tiket_kode_booking' => $tiket->kode_booking,
                    'tgl_issued'         => $tiket->tgl_issued,
                    'tgl_bayar'          => null,
                    'harga_bayar'        => $tiket->harga_jual,
                    'jenis_bayar_id'     => 3,
                    'piutang_id'         => $piutang->id,
                    'keterangan'         => 'PIUTANG',
                ]);
            }

            // stok tiket
            JenisTiket::find($tiket->jenis_tiket_id)
                ->decrement('saldo', $tiket->nta);

            // kas masuk
            JenisBayar::find($request->jenis_bayar_id)
                ->increment('saldo', $tiket->harga_jual);

            if ((int)$request->jenis_bayar_id === 1 && $request->bank_id) {
                $bank = Bank::lockForUpdate()->findOrFail($request->bank_id);

                $saldoAwal = $bank->saldo;
                $saldoAkhir = $saldoAwal + $tiket->harga_jual;

                // update saldo bank
                $bank->update([
                    'saldo' => $saldoAkhir
                ]);

                // CATAT BUKU BANK
                MutasiBank::create([
                    'bank_id'    => $bank->id,
                    'tanggal'    => $tiket->tgl_issued,
                    'debit'      => $tiket->harga_jual,
                    'kredit'     => 0,
                    'saldo'      => $saldoAkhir,
                    'keterangan' => 'Penjualan tiket ' . $tiket->kode_booking,
                ]);
            }


            // mutasi tiket
            $mutasiService->create([
                'tiket_kode_booking' => $tiket->kode_booking,
                'tgl_issued'         => $tiket->tgl_issued,
                'tgl_bayar'          => $request->jenis_bayar_id == 3 ? null : $tiket->tgl_issued,
                'harga_bayar'        => $tiket->nta * -1,
                'jenis_bayar_id'     => $request->jenis_bayar_id,
                'bank_id'            => $request->bank_id,
                'nama_piutang'       => $request->jenis_bayar_id == 3 ? $request->nama_piutang : null,
                'keterangan'         => $request->keterangan,
            ]);
        });

        return back()->with('success', 'Tiket berhasil dibuat');
    }


    public function update(
        Request $request,
        string $kode_booking,
        MutasiTiketService $mutasiService
    ) {
        $request->validate([
            'kode_booking'   => 'required|string|max:10',
            'tgl_issued'     => 'required|date',
            'name'           => 'required|string|max:100',
            'harga_jual'     => 'required|integer|min:0',
            'nta'            => 'required|integer|min:0',
            'diskon'         => 'required|integer|min:0',
            'komisi'         => 'required|integer|min:0',
            'rute'           => 'required|string|max:45',
            'tgl_flight'     => 'required|date',
            'rute2'          => 'nullable|string|max:45',
            'tgl_flight2'    => 'nullable|date',
            'status'        => 'required|in:issued,canceled,refunded',
            'jenis_bayar_id' => 'nullable|exists:jenis_bayar,id',
            'bank_id'       => 'nullable|exists:bank,id',
            'nilai_refund'  => 'nullable|integer|min:0',
            'tgl_realisasi' => 'nullable|date',
            'keterangan'    => 'nullable|string|max:200',

            'subagent_id'   => 'nullable|exists:subagents,id',
        ]);

        DB::transaction(function () use ($request, $kode_booking, $mutasiService) {

            $tiket = Tiket::lockForUpdate()
                ->where('kode_booking', $kode_booking)
                ->firstOrFail();

            $statusLama = $tiket->status;

            // kalau refund stop program
            if ($statusLama === 'refunded') {
                throw new \Exception('Tiket refund tidak bisa diubah');
            }

            // =========================
            // UPDATE DATA UMUM
            // =========================
            $tiket->update([
                'kode_booking'   => strtoupper($request->kode_booking),
                'tgl_issued'     => $request->tgl_issued,
                'name'           => strtoupper($request->name),
                'harga_jual'     => $request->harga_jual,
                'nta'            => $request->nta,
                'diskon'         => $request->diskon,
                'komisi'         => $request->harga_jual - $request->diskon - $request->nta,
                'rute'           => strtoupper($request->rute),
                'tgl_flight'     => $request->tgl_flight,
                'rute2'          => strtoupper($request->rute2),
                'tgl_flight2'    => $request->tgl_flight2,
                'status'         => $request->status,
                'jenis_tiket_id' => $request->jenis_tiket_id,

                'nama_piutang'   => $request->jenis_bayar_id == 3 ? $request->nama_piutang : null,
                'keterangan'     => strtoupper($request->keterangan),
                'nilai_refund'  => $request->nilai_refund,
                'tgl_realisasi' => $request->tgl_realisasi,
            ]);

            if ($tiket->status === 'issued') {

                $mutasi = MutasiTiket::where('tiket_kode_booking', $tiket->kode_booking)
                    ->where('harga_bayar', '>', 0)
                    ->lockForUpdate()
                    ->first();

                if ($mutasi) {
                    // UPDATE mutasi lama
                    $mutasi->update([
                        'tgl_bayar'      => $tiket->tgl_issued,
                        'harga_bayar'    => $tiket->harga_jual,
                        'jenis_bayar_id' => $tiket->jenis_bayar_id,
                        'bank_id'        => $request->bank_id,
                        'nama_piutang'   => $request->jenis_bayar_id == 3 ? $request->nama_piutang : null,
                        'keterangan'     => 'UPDATE DATA TIKET',
                    ]);
                } else {
                    // kalau belum ada (subagent)
                    $mutasiService->create([
                        'tiket_kode_booking' => $tiket->kode_booking,
                        'tgl_bayar'          => $tiket->tgl_issued,
                        'harga_bayar'        => $tiket->harga_jual,
                        'jenis_bayar_id'     => 4,
                        'bank_id'            => $request->bank_id,
                        'nama_piutang'       => $request->jenis_bayar_id == 3 ? $request->nama_piutang : null,
                        'keterangan'         => 'ISSUED TIKET',
                    ]);
                }
            }

            // =========================
            // 🔄 ISSUED → REFUNDED
            // =========================
            if ($statusLama === 'issued' && $tiket->status === 'refunded') {

                

                if (
                    !$request->filled('nilai_refund') ||
                    !$request->filled('tgl_realisasi')
                ) {
                    throw new \Exception('Nilai refund & tanggal realisasi wajib diisi');
                }
                JenisTiket::lockForUpdate()
                    ->findOrFail($request->jenis_tiket_id)
                    ->increment('saldo', $request->nilai_refund);

                // =========================
                // 🟣 REFUND SUBAGENT
                // =========================
                if ($request->subagent_id) {

                    $subagent = Subagent::lockForUpdate()
                        ->findOrFail($request->subagent_id);

                    // saldo subagent naik
                    $subagent->increment('saldo', $request->nilai_refund);

                    
                    // history subagent
                    SubagentHistory::create([
                        'tgl_issued'   => $tiket->tgl_realisasi,
                        'subagent_id'  => $subagent->id,
                        'kode_booking' => $tiket->kode_booking,
                        'status'       => 'refunded',
                        'transaksi'    => $tiket->nilai_refund,
                    ]);

                } 
                // =========================
                // 🟢 REFUND CUSTOMER
                // =========================
                else {

                    if ($request->bank_id) {
                        $bank = Bank::lockForUpdate()
                            ->findOrFail($request->bank_id);

                        $saldoAwal = $bank->saldo;
                        $saldoAkhir = $saldoAwal - $request->nilai_refund;

                        // update saldo bank
                        $bank->update([
                            'saldo' => $saldoAkhir
                        ]);

                        // =========================
                        // MUTASI BANK (REFUND)
                        // =========================
                        MutasiBank::create([
                            'bank_id'    => $bank->id,
                            'tanggal'    => $tiket->tgl_realisasi,
                            'debit'      => 0,
                            'kredit'     => $tiket->nilai_refund,
                            'saldo'      => $saldoAkhir,
                            'keterangan' => 'Refund tiket ' . $tiket->kode_booking,
                        ]);
                    }
                    

                    if ($request->jenis_bayar_id) {
                        $jenisBayar = JenisBayar::lockForUpdate()
                            ->findOrFail($request->jenis_bayar_id);

                        $jenisBayar->decrement('saldo', $tiket->nilai_refund);
                    }

                    // mutasi refund
                    $mutasiService->create([
                        'tiket_kode_booking' => $tiket->kode_booking,
                        'tgl_bayar'          => $tiket->tgl_realisasi,
                        'harga_bayar'        => -$tiket->nilai_refund,
                        'jenis_bayar_id'     => $request->jenis_bayar_id,
                        'bank_id'            => $request->bank_id,
                        'keterangan'         => 'REFUND TIKET',
                    ]);
                }
            }
        });

        return back()->with('success', 'Tiket berhasil diperbarui');
    }

    public function searchPiutang(Request $request)
    {
        return Piutang::where('nama', 'LIKE', "%{$request->q}%")
            ->limit(10)
            ->get();
    }


    /**
     * Hapus tiket dan nota terkait
     */
    public function destroy($kode_booking)
    {
        DB::beginTransaction();
        
        try {
            // Hapus nota terkait terlebih dahulu (cascade)
            MutasiTiket::where('tiket_kode_booking', $kode_booking)->delete();
            
            // Hapus tiket
            $tiket = Tiket::where('kode_booking', $kode_booking)->first();
            
            if (!$tiket) {
                throw new \Exception('Data tiket tidak ditemukan.');
            }
            
            $tiket->delete();
            
            DB::commit();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Data tiket dan nota berhasil dihapus.'
                ]);
            }
            
            return redirect()->route('input-tiket.index')
                ->with('success', 'Data tiket dan nota berhasil dihapus.');
                
        } catch (\Exception $e) {
            DB::rollback();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Gagal menghapus data: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Cari tiket berdasarkan kode booking atau nama
     */
    public function search(Request $request)
    {
        $q = strtoupper($request->query('q'));

        $query = Tiket::with('jenisTiket')
            ->orderBy('tgl_issued', 'desc');

        if ($q && strlen($q) >= 2) {
            $query->where(function ($sub) use ($q) {
                $sub->where('kode_booking', 'like', "%{$q}%")
                    ->orWhere('name', 'like', "%{$q}%");
            });
        }

        return response()->json($query->get());
    }
    /**
     * Menampilkan halaman invoice untuk tiket yang dipilih
     */
    public function showInvoice(Request $request)
    {
        $ids = explode(',', $request->query('ids'));
        $data = Tiket::whereIn('kode_booking', $ids)->get();

        return view('invoice', compact('data'));
    }

    public function byTiket($kode)
    {
        $mutasi = MutasiTiket::where('tiket_kode_booking', $kode)->first();
        $tiket  = Tiket::where('kode_booking', $kode)->with('subagentHistories')->first();

        // ambil subagent terakhir (jika ada history)
        $subagentHistory = $tiket
            ? $tiket->subagentHistories()->latest('tgl_issued')->first()
            : null;

        return response()->json([
            'jenis_bayar_id' => $mutasi->jenis_bayar_id ?? null,
            'bank_id'        => $mutasi->bank_id ?? null,
            'nama_piutang'   => $mutasi->nama_piutang ?? null,
            'nilai_refund'   => $tiket->nilai_refund ?? 0,
            // dikirim dalam format yang mudah dipakai oleh input datetime-local di JS
            'tgl_realisasi'  => $tiket && $tiket->tgl_realisasi
                ? Carbon::parse($tiket->tgl_realisasi)->format('Y-m-d H:i:s')
                : null,
            'subagent_id'    => $subagentHistory->subagent_id ?? null,
        ]);
    }

}