<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class InputDataController extends Controller
{
    public function index()
    {
        $today = now()->format('Y-m-d');

        $tikets = Tiket::whereDate('tglIssued', $today)
            ->orWhereDate('tglRealisasi', $today)
            ->orderBy('tglIssued')
            ->orderBy('jam')
            ->get();

        return view('input-data', compact('tikets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tglIssued' => 'required|date',
            'kodeBooking' => 'required|string',
            'airlines' => 'required|string',
            'nama' => 'required|string',
            'rute1' => 'required|string',
            'tglFlight1' => 'required|date',
            'harga' => 'required|numeric',
            'nta' => 'required|numeric',
            'diskon' => 'required|numeric',
            'pembayaran' => 'required|string',
        ]);

        $kodeBookingInput = strtoupper($request->kodeBooking ?? $request->kode_booking);
        if (empty($request->tiket_id)) {
            $exists = Tiket::where('kodeBooking', $kodeBookingInput)->exists();
            if ($exists) {
                return redirect()->back()->withInput($request->all())
                    ->with('error', 'Kode Booking sudah digunakan, silakan masukkan kode lain.');
            }
        } else {
            $existsOther = Tiket::where('kodeBooking', $kodeBookingInput)
                ->where('id', '<>', $request->tiket_id)
                ->exists();
            if ($existsOther) {
                return redirect()->back()->withInput($request->all())
                    ->with('error', 'Kode Booking sudah digunakan oleh data lain.');
            }
        }

        $komisi = ($request->harga ?? 0) - ($request->nta ?? 0) - ($request->diskon ?? 0);

        $jam = Carbon::now()->format('H:i'); 
        $jamRealisasi = null;
        if (!empty($request->tglRealisasi ?? $request->tgl_realisasi) && !empty($request->jamRealisasi ?? $request->jam_realisasi)) {
            $jamRealisasi = is_string($request->jamRealisasi ?? $request->jam_realisasi) 
                ? $request->jamRealisasi ?? $request->jam_realisasi 
                : Carbon::parse($request->tglRealisasi ?? $request->tgl_realisasi)->format('H:i');
        }

        $tiket = Tiket::updateOrCreate(
            ['id' => $request->tiket_id],
            [
                'tglIssued'     => $request->tglIssued ?? $request->tgl_issued,
                'jam'           => $jam, 
                'kodeBooking'   => $kodeBookingInput,
                'airlines'      => strtoupper($request->airlines),
                'nama'          => strtoupper($request->nama),
                'rute1'         => strtoupper($request->rute1),
                'tglFlight1'    => $request->tglFlight1 ?? $request->tgl_flight1,
                'rute2'         => ($request->rute2 ?? null) ? strtoupper($request->rute2) : null,
                'tglFlight2'    => $request->tglFlight2 ?? $request->tgl_flight2 ?? null,
                'harga'         => (int)($request->harga ?? 0),
                'nta'           => (int)($request->nta ?? 0),
                'diskon'        => (int)($request->diskon ?? 0),
                'komisi'        => (int)$komisi,
                'pembayaran'    => strtoupper($request->pembayaran),
                'namaPiutang'   => ($request->namaPiutang ?? $request->nama_piutang ?? null) ? strtoupper($request->namaPiutang ?? $request->nama_piutang) : null,
                'tglRealisasi'  => $request->tglRealisasi ?? $request->tgl_realisasi ?? null,
                'jamRealisasi'  => $jamRealisasi,
                'nilaiRefund'   => (int)($request->nilaiRefund ?? $request->nilai_refund ?? 0),
                'keterangan'    => ($request->keterangan ?? null) ? strtoupper($request->keterangan) : null,
                'username'      => Auth::user()->username ?? Auth::user()->name,
            ]
        );

        $banks = ['BCA', 'BTN', 'BNI', 'MANDIRI', 'BRI'];
        if (in_array(strtoupper($request->pembayaran), $banks)) {
            Bank::updateOrCreate(
                ['id_tiket' => $tiket->id],
                [
                    'nama_bank'   => strtoupper($request->pembayaran),
                    'total_harga' => $request->harga - ($request->nta ?? 0),
                ]
            );
        }

        $msg = $request->tiket_id ? 'Data berhasil diperbarui!' : 'Data berhasil disimpan!';
        return redirect()->route('input-data.index')->with('success', $msg);
    }

    public function destroy($id)
    {
        $tiket = Tiket::find($id);

        if (!$tiket) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Data tiket tidak ditemukan.'], 404);
            }
            return redirect()->back()->with('error', 'Data tiket tidak ditemukan.');
        }

        Bank::where('id_tiket', $id)->delete();

        $tiket->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Data tiket berhasil dihapus.']);
        }

        return redirect()->route('input-data.index')->with('success', 'Data tiket berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $search = strtoupper($request->search);

        if (strlen($search) < 3) {
            return redirect()->route('input-data.index')->with('error', 'Karakter pencarian minimal 3 huruf!');
        }

        $tikets = Tiket::where('kodeBooking', 'like', "%{$search}%")
            ->orWhere('nama', 'like', "%{$search}%")
            ->get();

        return view('input-data', compact('tikets'));
    }

    public function getTiket($id)
    {
        $tiket = Tiket::findOrFail($id);
        return response()->json($tiket);
    }

    public function invoice(Request $request)
    {
        $ids = explode(',', $request->ids);
        $tikets = Tiket::whereIn('id', $ids)->get();

        return view('invoice', compact('tikets'));
    }
}
