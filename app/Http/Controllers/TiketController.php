<?php

namespace App\Http\Controllers;

use App\Services\MutasiTiketService;
use App\Models\Tiket;
use App\Models\JenisTiket;
use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\MutasiTiket;
use App\Models\Subagent;
use App\Models\TopupHistory;
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
        $query = Tiket::query()->with('jenisTiket');

        if ($request->filled('kode_booking')) {
            $query->where('kode_booking', 'like', '%' . strtoupper($request->kode_booking) . '%');
        }

        if ($request->filled('nama_pax')) {
            $query->where('name', 'like', '%' . strtoupper($request->nama_pax) . '%');
        }

        if ($request->filled('tgl_flight')) {
            $query->whereDate('tgl_flight', $request->tgl_flight);
        }

        if ($request->filled('tgl_issued')) {
            $query->whereDate('tgl_issued', $request->tgl_issued);
        }

        // 🔹 nama piutang ada di NOTA
        if ($request->filled('nama_piutang')) {
            $query->whereHas('nota', function ($q) use ($request) {
                $q->where('nama', 'like', '%' . strtoupper($request->nama_piutang) . '%');
            });
        }

        $ticket = $query->orderBy('tgl_issued', 'desc')->get();

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
                $jenisTiket = JenisTiket::findOrFail($request->jenis_tiket_id);
                $jenisTiket->increment('saldo', $request->topup);

                // 2️⃣ SIMPAN MUTASI TOPUP HISTORY
                TopupHistory::create([
                    'tgl_issued'      => $request->tanggal,
                    'transaksi'       => $request->topup,
                    'jenis_tiket_id'  => $request->jenis_tiket_id,
                    'subagent_id'     => null,
                    'jenis_bayar_id'  => $request->jenis_bayar_id,
                    'bank_id'         => $request->jenis_bayar_id == 1
                        ? $request->bank_id
                        : null,
                    'keterangan'      => $request->keterangan,
                ]);
            }

            DB::commit();
            return back()->with('success', 'Mutasi berhasil disimpan');

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
        $request->validate([
            'tgl_issued'     => 'required|date',
            'kode_booking'   => 'required|string|max:10',
            'name'           => 'required|string|max:100',
            'harga_jual'     => 'required|integer',
            'nta'            => 'required|integer',
            'diskon'         => 'required|integer',
            'komisi'         => 'required|integer',
            'rute'           => 'required|string|max:45',
            'tgl_flight'     => 'required|date',
            'rute2'          => 'nullable|string|max:45',
            'tgl_flight2'    => 'nullable|date',
            'status'         => 'required|in:issued,canceled,refunded',
            'jenis_tiket_id' => 'required|exists:jenis_tiket,id',
            'jenis_bayar_id' => 'required|exists:jenis_bayar,id',
            'bank_id'        => 'nullable|exists:bank,id',
            'keterangan'     => 'nullable|string|max:255',
            'nama_piutang'   => 'nullable|string|max:100',
            'nilai_refund'   => 'nullable|integer|min:0',
            'tgl_realisasi'  => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            $kodeBookingInput = strtoupper($request->kode_booking);

            // Kurangi saldo pada jenis_tiket
            $jenisTiket = JenisTiket::findOrFail($request->jenis_tiket_id);
            $jenisTiket->decrement('saldo', $request->nta);

            // Tambah saldo pada jenis_bayar
            $jenisBayar = JenisBayar::findOrFail($request->jenis_bayar_id);
            $jenisBayar->increment('saldo', $request->harga_bayar);

            // 1️⃣ SIMPAN / UPDATE TIKET
            $tiket = Tiket::updateOrCreate(
                ['kode_booking' => $kodeBookingInput],
                [
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
                    'status'         => $request->status,
                    'jenis_tiket_id' => $request->jenis_tiket_id,
                    'keterangan'     => strtoupper($request->keterangan),
                    'nilai_refund'   => $request->nilai_refund,
                    'tgl_realisasi'  => $request->tgl_realisasi,
                ]
            );

            // 2️⃣ CATAT MUTASI TIKET (PENGGANTI NOTA)
            if ($request->status === 'issued') {
                $mutasiService->create([
                'tiket_kode_booking' => $tiket->kode_booking,
                'tgl_issued'     => $tiket->tgl_issued,
                'tgl_bayar' => $request->jenis_bayar_id == 3
                    ? null
                    : $request->tgl_realisasi ?? $tiket->tgl_issued,
                'harga_bayar'    => $tiket->harga_jual,
                'jenis_bayar_id' => $request->jenis_bayar_id,
                'bank_id'        => $request->bank_id,
                'nama_piutang' => $request->jenis_bayar_id == 3
                    ? $request->nama_piutang
                    : null,
                'keterangan' => $request->keterangan
                    ? strtoupper($request->keterangan)
                    : null,
                ]);
            }

            


            DB::commit();
            return redirect()->route('input-tiket.index')->with('success', 'OK');

            } catch (\Throwable $e) {
                dd($e->getMessage());
                DB::rollBack();
            }
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

        return response()->json([
            'jenis_bayar_id' => $mutasi->jenis_bayar_id ?? null,
            'bank_id'        => $mutasi->bank_id ?? null,
            'nama_piutang'   => $mutasi->nama_piutang ?? null,
        ]);
    }

}