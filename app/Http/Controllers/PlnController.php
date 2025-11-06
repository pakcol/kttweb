<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pln;
use App\Models\PlnPiutang;
use Illuminate\Support\Facades\Schema;

class PlnController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('pln')) {
            $data = collect([
                (object)[
                    'no_pel' => '1234567890',
                    'pulsa' => 100000,
                    'nta' => 5000,
                    'tgl' => '2025-11-07',
                    'bayar' => 'TUNAI',
                    'nama_piutang' => '-'
                ],
                (object)[
                    'no_pel' => '9876543210',
                    'pulsa' => 150000,
                    'nta' => 7500,
                    'tgl' => '2025-11-06',
                    'bayar' => 'BCA',
                    'nama_piutang' => 'PT. Alfa Elektronik'
                ],
                (object)[
                    'no_pel' => '5647382910',
                    'pulsa' => 200000,
                    'nta' => 10000,
                    'tgl' => '2025-11-05',
                    'bayar' => 'PIUTANG',
                    'nama_piutang' => 'CV. Kupang Listrik'
                ],
            ]);
        } else {
            $data = Pln::all();
        }

        return view('pln', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'no_pel' => 'required|string|max:255',
            'pulsa' => 'nullable|string',
            'nta' => 'nullable|string',
            'tgl' => 'nullable|date',
            'bayar' => 'required|string',
            'nama_piutang' => 'nullable|string',
        ]);

        if (Schema::hasTable('pln')) {
            Pln::create($validated);
        }

        return redirect()->route('pln.index')->with('success', 'Data PLN berhasil disimpan!');
    }

    public function indexPiutang()
    {
        if (!Schema::hasTable('pln')) {
            // ✅ Data dummy menyesuaikan field yang dipakai di Blade (tanggal, jam, id_pel, dll)
            $piutang = collect([
                (object)[
                    'tanggal' => '2025-11-06',
                    'jam' => '10:45:00',
                    'id_pel' => 'PLN001',
                    'harga_jual' => 150000,
                    'transaksi' => 'TOKEN 100K',
                    'bayar' => 'PIUTANG',
                    'nama_piutang' => 'CV. Sinar Kupang',
                    'top_up' => 50000,
                    'insentif' => 2500,
                    'saldo' => 1000000,
                    'usr' => 'Admin1',
                    'tgl_realisasi' => '2025-11-07',
                    'jam_realisasi' => '14:20:00'
                ],
                (object)[
                    'tanggal' => '2025-11-05',
                    'jam' => '09:30:00',
                    'id_pel' => 'PLN002',
                    'harga_jual' => 200000,
                    'transaksi' => 'TOKEN 200K',
                    'bayar' => 'PIUTANG',
                    'nama_piutang' => 'PT. Alfa Energi',
                    'top_up' => 100000,
                    'insentif' => 5000,
                    'saldo' => 850000,
                    'usr' => 'Admin2',
                    'tgl_realisasi' => '2025-11-06',
                    'jam_realisasi' => '13:00:00'
                ],
                (object)[
                    'tanggal' => '2025-11-04',
                    'jam' => '11:15:00',
                    'id_pel' => 'PLN003',
                    'harga_jual' => 120000,
                    'transaksi' => 'TOKEN 50K',
                    'bayar' => 'BCA',
                    'nama_piutang' => '-',
                    'top_up' => 0,
                    'insentif' => 0,
                    'saldo' => 750000,
                    'usr' => 'Admin3',
                    'tgl_realisasi' => '2025-11-05',
                    'jam_realisasi' => '12:10:00'
                ],
            ]);
        } else {
            // Jika tabel tersedia, ambil dari DB
            $piutang = Pln::piutang()->get();
        }

        return view('plnPiutang', compact('piutang'));
    }

    public function showPiutang($id)
    {
        if (Schema::hasTable('pln')) {
            $data = Pln::piutang()->find($id);
        } else {
            // Dummy single data
            $dummy = collect([
                (object)[
                    'id' => 1,
                    'tanggal' => '2025-11-06',
                    'jam' => '10:45:00',
                    'id_pel' => 'PLN001',
                    'harga_jual' => 150000,
                    'transaksi' => 'TOKEN 100K',
                    'bayar' => 'PIUTANG',
                    'nama_piutang' => 'CV. Sinar Kupang',
                    'top_up' => 50000,
                    'insentif' => 2500,
                    'saldo' => 1000000,
                    'usr' => 'Admin1',
                    'tgl_realisasi' => '2025-11-07',
                    'jam_realisasi' => '14:20:00'
                ],
            ]);
            $data = $dummy->firstWhere('id', $id);
        }

        if (!$data) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($data);
    }
}
