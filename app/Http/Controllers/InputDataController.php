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

        $tikets = Tiket::whereDate('tgl_issued', $today)
            ->orWhereDate('tgl_realisasi', $today)
            ->orderBy('tgl_issued')
            ->orderBy('jam_input')
            ->get();

        return view('input-data', compact('tikets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl_issued' => 'required|date',
            'kode_booking' => 'required|string',
            'airlines' => 'required|string',
            'nama' => 'required|string',
            'rute1' => 'required|string',
            'tgl_flight1' => 'required|date',
            'harga' => 'required|numeric',
            'nta' => 'required|numeric',
            'diskon' => 'required|numeric',
            'pembayaran' => 'required|string',
        ]);

        $kodeBookingInput = strtoupper($request->kode_booking);
        if (empty($request->tiket_id)) {
            $exists = Tiket::where('kode_booking', $kodeBookingInput)->exists();
            if ($exists) {
                return redirect()->back()->withInput($request->all())
                    ->with('error', 'Kode Booking sudah digunakan, silakan masukkan kode lain.');
            }
        } else {
            $existsOther = Tiket::where('kode_booking', $kodeBookingInput)
                ->where('id', '<>', $request->tiket_id)
                ->exists();
            if ($existsOther) {
                return redirect()->back()->withInput($request->all())
                    ->with('error', 'Kode Booking sudah digunakan oleh data lain.');
            }
        }

        $komisi = $request->harga - $request->nta - $request->diskon;

        $jamInput = Carbon::now(); 
        $jamRealisasi = null;
        if (!empty($request->tgl_realisasi) && !empty($request->jam_realisasi)) {
            $jamRealisasi = Carbon::parse($request->tgl_realisasi . ' ' . $request->jam_realisasi);
        }

        $tiket = Tiket::updateOrCreate(
            ['id' => $request->tiket_id],
            [
                'tgl_issued'     => $request->tgl_issued,
                'jam_input'      => $jamInput, 
                'kode_booking'   => $kodeBookingInput,
                'airlines'       => strtoupper($request->airlines),
                'nama'           => strtoupper($request->nama),
                'rute1'          => strtoupper($request->rute1),
                'tgl_flight1'    => $request->tgl_flight1,
                'rute2'          => $request->rute2 ? strtoupper($request->rute2) : null,
                'tgl_flight2'    => $request->tgl_flight2,
                'harga'          => $request->harga,
                'nta'            => $request->nta,
                'diskon'         => $request->diskon,
                'komisi'         => $komisi,
                'pembayaran'     => strtoupper($request->pembayaran),
                'nama_piutang'   => $request->nama_piutang ? strtoupper($request->nama_piutang) : null,
                'tgl_realisasi'  => $request->tgl_realisasi,
                'jam_realisasi'  => $jamRealisasi,
                'nilai_refund'   => $request->nilai_refund ?? 0,
                'keterangan'     => $request->keterangan ? strtoupper($request->keterangan) : null,
                'usr'            => Auth::user()->name,
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

        $tikets = Tiket::where('kode_booking', 'like', "%{$search}%")
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
