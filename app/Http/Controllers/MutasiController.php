<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MutasiController extends Controller
{
    public function index()
    {
        $allData = $this->getAllAirlinesData();
        return view('mutasi', compact('allData'));
    }

    private function getAllAirlinesData()
    {
        $username = Auth::user()->username ?? 'default_user';

        $citilinkData = DB::table('citilink')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'CITILINK';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $garudaData = DB::table('garuda')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'GARUDA';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $lionData = DB::table('lion')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'LION';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $sriwijayaData = DB::table('sriwijaya')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'SRWIJAYA';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $qgcornerData = DB::table('qgcorner')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'QGCORNER';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $transnusaData = DB::table('transnusa')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'TRANSNUSA';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $pelniData = DB::table('pelni')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'PELNI';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $airasiaData = DB::table('airasia')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'AIRASIA';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $dluData = DB::table('dlu')
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->airlines = 'DLU';
                $item->tanggal = $item->tgl;
                return $item;
            });

        // Gabungkan semua data dan urutkan berdasarkan tanggal dan ID
        return $citilinkData->concat($garudaData)
                        ->concat($lionData)
                        ->concat($sriwijayaData)
                        ->concat($qgcornerData)
                        ->concat($transnusaData)
                        ->concat($pelniData)
                        ->concat($airasiaData)
                        ->concat($dluData)
                        ->sortByDesc('tgl')
                        ->sortByDesc('id');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'bank' => 'required|string',
            'topup' => 'nullable|numeric|min:0',
            'biaya' => 'nullable|numeric|min:0',
            'insentif' => 'nullable|numeric|min:0',
            'airlines' => 'required|string',
            'keterangan' => 'nullable|string|max:30'
        ]);

        // Dapatkan username dari user yang login
        $username = Auth::user()->username ?? 'default_user';

        // Konversi ke integer (karena kolom di database adalah integer)
        $topup = (int) ($request->topup ?? 0);
        $biaya = (int) ($request->biaya ?? 0);
        $insentif = (int) ($request->insentif ?? 0);

        // Hitung saldo untuk airlines yang dipilih
        $saldo = $this->calculateSaldoAirlines($request->airlines, $topup, $biaya, $insentif, $username);

        try {
            // Simpan ke tabel airlines yang dipilih
            $tableName = $request->airlines;
            
            DB::table($tableName)->insert([
                'tgl' => $request->tanggal,
                'jam' => now()->format('H:i:s'),
                'top_up' => $topup,
                'transaksi' => $biaya, // biaya sebagai transaksi
                'insentif' => $insentif,
                'saldo' => $saldo,
                'keterangan' => $request->keterangan ?? '',
                'username' => $username,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Juga simpan ke tabel bank yang dipilih
            $this->saveToBank($request->bank, $request->tanggal, $topup, $biaya, $insentif, $request->keterangan ?? '', $username);

            return redirect()->route('mutasi-airlines.store')->with('success', 'Data berhasil disimpan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // Method untuk menghitung saldo airlines
    private function calculateSaldoAirlines($airlines, $topup, $transaksi, $insentif, $username)
    {
        // Ambil saldo terakhir dari airlines yang dipilih
        $lastSaldo = $this->getLastSaldoAirlines($airlines, $username);
        
        // Hitung saldo baru: saldo lama + topup - transaksi + insentif
        $newSaldo = $lastSaldo + $topup - $transaksi + $insentif;
        
        return $newSaldo;
    }

    // Method untuk mendapatkan saldo terakhir airlines
    private function getLastSaldoAirlines($airlines, $username)
    {
        $lastRecord = DB::table($airlines)
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('jam', 'desc')
            ->first();
        
        return $lastRecord ? $lastRecord->saldo : 0;
    }

    // Method untuk menyimpan data ke tabel bank
    private function saveToBank($bank, $tanggal, $topup, $transaksi, $insentif, $keterangan, $username)
    {
        $tableName = strtolower($bank);
        
        // Hitung saldo untuk bank
        $saldoBank = $this->calculateSaldoBank($tableName, $topup, $transaksi, $username);

        DB::table($tableName)->insert([
            'tgl' => $tanggal,
            'keterangan' => $keterangan,
            'credit' => $transaksi, // transaksi sebagai credit di bank
            'debit' => $topup,      // topup sebagai debit di bank
            'saldo' => $saldoBank,
            'username' => $username,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Method untuk menghitung saldo bank
    private function calculateSaldoBank($bank, $topup, $transaksi, $username)
    {
        // Ambil saldo terakhir dari bank
        $lastSaldo = $this->getLastSaldoBank($bank, $username);
        
        // Hitung saldo baru sesuai formula: saldo lama + debit - credit
        $newSaldo = $lastSaldo + $topup - $transaksi;
        
        return $newSaldo;
    }

    // Method untuk mendapatkan saldo terakhir bank
    private function getLastSaldoBank($bank, $username)
    {
        $lastRecord = DB::table($bank)
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        
        return $lastRecord ? $lastRecord->saldo : 0;
    }

    public function show($airlines)
    {
        if (!DB::getSchemaBuilder()->hasTable($airlines)) {
            return back()->with('error', 'Tabel tidak ditemukan!');
        }

        $username = Auth::user()->username ?? 'default_user';

        $data = DB::table($airlines)
                ->where('username', $username)
                ->orderBy('tgl', 'desc')
                ->orderBy('jam', 'desc')
                ->get();

        return view('mutasi', compact('data', 'airlines'));
    }

    // Method untuk menampilkan semua data dari semua airlines
    public function showAll()
    {
        $username = Auth::user()->username ?? 'default_user';
        $airlines = ['citilink', 'garuda', 'lion', 'sriwijaya', 'qgcorner', 'transnusa', 'pelni', 'airasia', 'dlu'];
        
        $allData = collect();

        foreach ($airlines as $airline) {
            if (DB::getSchemaBuilder()->hasTable($airline)) {
                $data = DB::table($airline)
                    ->where('username', $username)
                    ->get()
                    ->map(function($item) use ($airline) {
                        $item->airline_name = strtoupper($airline);
                        return $item;
                    });
                
                $allData = $allData->concat($data);
            }
        }

        // Urutkan berdasarkan tanggal dan jam
        $allData = $allData->sortByDesc('tgl')->sortByDesc('jam');

        return view('mutasi', compact('allData'));
    }
}