<?php

namespace App\Http\Controllers;

use App\Models\MutasiTiket;
use App\Models\Tiket;
use App\Models\JenisBayar;
use App\Models\Bank;
use Illuminate\Http\Request;

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
}
