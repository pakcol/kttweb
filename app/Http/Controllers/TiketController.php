<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use App\Models\Nota;
use App\Models\JenisTiket;
use App\Models\JenisBayar;
use App\Models\Bank;
use App\Models\Biaya;
use App\Models\Subagent;
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
            'ticket'      => Tiket::with(['jenisTiket'])->latest()->get(),
            'jenisBayar'  => JenisBayar::all(),
            'jenisBayarNonPiutang' => JenisBayar::where('id', '!=', 3)->get(),
            'bank'        => Bank::all(),
            'jenisTiket'  => JenisTiket::all(),
        ]);
    }

    public function indexMutasi(Request $request)
    {
        $jenisBayar = JenisBayar::where('id', '!=', 3)->get();
        $bank = Bank::all();

        // Dropdown jenis tiket
        $jenisTiket = JenisTiket::orderBy('name_jenis')->get();
        $jenisTiketId = $request->jenis_tiket_id ?? $jenisTiket->first()?->id;

        /* ================= KELUAR (PEMBELIAN TIKET) ================= */
        $nota = DB::table('nota')
            ->join('tiket', 'nota.tiket_kode_booking', '=', 'tiket.kode_booking')
            ->where('tiket.jenis_tiket_id', $jenisTiketId)
            ->whereNotNull('nota.tgl_bayar')
            ->select(
                'nota.id as order_id',
                'nota.tgl_bayar as tanggal',
                DB::raw('-nota.harga_bayar as transaksi'),
                DB::raw("'Pembelian Tiket' as keterangan")
            )
            ->get();

        /* ================= MASUK (TOP UP TIKET) ================= */
        $biaya = DB::table('biaya')
            ->where('kategori', 'top_up')
            ->where('id_jenis_tiket', $jenisTiketId)
            ->select(
                'biaya.id as order_id',
                'tgl as tanggal',
                'biaya as transaksi',
                DB::raw("COALESCE(keterangan, 'Top Up Tiket') as keterangan")
            )
            ->get();

        /* ================= GABUNG & SORT ASC ================= */
        $mutasi = collect()
            ->merge($nota)
            ->merge($biaya)
            ->sortBy([
                ['tanggal', 'asc'],
                ['order_id', 'asc'],
            ])
            ->values();

        /* ================= SALDO BERJALAN ================= */
        $saldo = 0;

        $mutasi = $mutasi->map(function ($row) use (&$saldo) {
            $saldo += $row->transaksi;
            $row->saldo = $saldo;
            return $row;
        });

        /* ================= BALIK UNTUK TAMPILAN (DESC) ================= */
        $mutasi = $mutasi
            ->sortByDesc([
                ['tanggal', 'desc'],
                ['order_id', 'desc'],
            ])
            ->values();

        return view('mutasi', [
            'jenisTiket'   => $jenisTiket,
            'jenisTiketId' => $jenisTiketId,
            'mutasi'       => $mutasi,
            'saldoTiket'   => $saldo,
            'jenisBayar'   => $jenisBayar,
            'bank'         => $bank,
        ]);
    }




    public function indexFind()
    {
        return view('find', [
            'tiket'      => Tiket::with(['jenisTiket'])->latest()->get(),
            'jenisBayar'  => JenisBayar::all(),
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
        $request->validate([
            'tanggal'         => 'required|date',
            'topup'           => 'required|numeric|min:1',
            'jenis_tiket_id'  => 'required|exists:jenis_tiket,id',
            'jenis_bayar_id'  => 'required|exists:jenis_bayar,id',
            'bank_id'         => 'nullable|exists:bank,id',
            'keterangan'      => 'nullable|string|max:30',
        ]);

        DB::beginTransaction();

        try {
            $jenisTiket = JenisTiket::findOrFail($request->jenis_tiket_id);
            /* ===================== SIMPAN KE BIAYA ===================== */
            Biaya::create([
                'tgl'             => $request->tanggal,
                'biaya'           => $request->topup,
                'kategori'       => 'top_up',
                'id_jenis_tiket'  => $request->jenis_tiket_id,
                'jenis_bayar_id'  => $request->jenis_bayar_id,
                'bank_id'         => $request->bank_id,
                'keterangan'      => $request->keterangan ?? 'Top Up Saldo Tiket '.$jenisTiket->name_jenis,
            ]);

            /* ===================== UPDATE SALDO JENIS TIKET ===================== */
            $jenisTiket->saldo += $request->topup;
            $jenisTiket->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Top up mutasi tiket berhasil');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Gagal menyimpan mutasi tiket');
        }
    }


    /**
     * Menyimpan tiket baru atau update tiket yang ada, plus simpan ke nota
     */
    public function store(Request $request)
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
        $kodeBookingInput = strtoupper($request->kode_booking);

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

        ]);

        $nota = Nota::updateOrCreate(
        ['tiket_kode_booking' => $tiket->kode_booking],
        [
            'nama' => $request->nama_piutang
                    ? $request->nama_piutang . ' - ' . $tiket->name
                    : $tiket->name,
            'tgl_issued' => $tiket->tgl_issued,
            'tgl_bayar' => $tiket->tgl_issued,
            'jenis_bayar_id' => $request->jenis_bayar_id,
            'bank_id' => $request->bank_id,
            'tiket_kode_booking' => $tiket->kode_booking,
            'harga_bayar' => $tiket->harga_jual,
        ]);

        if ($request->jenis_bayar_id != 3) { // 3 = PIUTANG
                Biaya::create([
                    'tgl'            => Carbon::now(),
                    'biaya'          => $tiket->nta,
                    'id_jenis_tiket' => $tiket->jenis_tiket_id,
                    'jenis_bayar_id' => $request->jenis_bayar_id,
                    'bank_id'        => $request->bank_id,
                    'keterangan'     => 'Pembelian tiket ' . $tiket->jenisTiket->name_jenis,
                ]);
            }

        DB::commit();

        return redirect()->route('input-tiket.index')->with('success', 'OK');
    }


    /**
     * Fungsi untuk menyimpan data ke tabel nota secara fleksibel
     */
    private function simpanKeNota(Tiket $tiket, Request $request)
    {
        // Tentukan tanggal bayar berdasarkan kondisi
        $tglBayar = null;
        
        // Jika status issued dan sudah ada tgl realisasi, gunakan tgl realisasi
        if ($tiket->status == 'issued' && $request->tgl_realisasi) {
            $tglBayar = $request->tgl_realisasi;
        } 
        // Jika status issued tapi belum realisasi, gunakan tgl issued
        elseif ($tiket->status == 'issued') {
            $tglBayar = $tiket->tgl_issued;
        }
        // Jika piutang dan sudah direalisasi
        elseif ($tiket->jenis_bayar_id == 3 && $request->tgl_realisasi) {
            $tglBayar = $request->tgl_realisasi;
        }
        
        // Tentukan harga bayar (harga jual - diskon - refund jika ada)
        $hargaBayar = $tiket->harga_jual - $tiket->diskon;
        
        // Jika ada refund, kurangkan dari harga bayar
        if ($tiket->status == 'refunded' && $request->nilai_refund > 0) {
            $hargaBayar -= $request->nilai_refund;
        }
        
        // Data untuk nota - FLEKSIBEL: hanya ambil data yang relevan
        $notaData = [
            'tgl_issued'           => $tiket->tgl_issued,
            'tgl_bayar'            => $tglBayar,
            'harga_bayar'          => $hargaBayar,
            'jenis_bayar_id'       => $tiket->jenis_bayar_id,
            'bank_id'              => $tiket->bank_id, // NULL jika bukan bank
            'tiket_kode_booking'   => $tiket->kode_booking,
        ];
        
        // Simpan atau update nota
        // Nota dibuat fleksibel: jika ada perubahan di tiket, nota otomatis update
        Nota::updateOrCreate(
            ['tiket_kode_booking' => $tiket->kode_booking],
            $notaData
        );
    }

    /**
     * Hapus tiket dan nota terkait
     */
    public function destroy($kode_booking)
    {
        DB::beginTransaction();
        
        try {
            // Hapus nota terkait terlebih dahulu (cascade)
            Nota::where('tiket_kode_booking', $kode_booking)->delete();
            
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
        $search = strtoupper($request->search);

        if (strlen($search) < 3) {
            return redirect()->route('input-tiket.index')
                ->with('error', 'Karakter pencarian minimal 3 huruf!');
        }

        $ticket = Tiket::where('kode_booking', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%")
            ->with(['jenisTiket', 'jenisBayar', 'bank'])
            ->get();

        $jenisTiket = JenisTiket::orderBy('name_jenis')->get();
        $jenisBayar = JenisBayar::orderBy('jenis')->get();
        $bank = Bank::orderBy('nama_bank')->get();

        return view('input-tiket', compact('ticket', 'jenisTiket', 'jenisBayar', 'bank'));
    }

    /**
     * Ambil data tiket berdasarkan ID (untuk AJAX)
     */
    public function getTiket($kode_booking)
    {
        return response()->json(
            Tiket::with(['jenisTiket'])
                ->where('kode_booking', $kode_booking)
                ->firstOrFail()
        );
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
}