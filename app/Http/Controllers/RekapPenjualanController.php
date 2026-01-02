<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RekapPenjualanController extends Controller
{
    public function index()
    {
        // Set default dates (current month)
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        
        $data = $this->getRekapData($startDate, $endDate);
        
        return view('rekap-penjualan', compact('data', 'startDate', 'endDate'));
    }

    public function tampil(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $data = $this->getRekapData($startDate, $endDate);

        return view('rekap-penjualan', compact('data', 'startDate', 'endDate'));
    }

    private function getRekapData($startDate, $endDate)
    {
        $username = Auth::user()->username ?? 'default_user';

        return [
            'penjualan' => $this->getDataPenjualan($startDate, $endDate, $username),
            'nta' => $this->getDataNta($startDate, $endDate, $username),
            'rincian' => $this->getDataRincian($startDate, $endDate, $username),
            'pln' => $this->getDataPln($startDate, $endDate, $username)
        ];
    }

    private function getDataPenjualan($startDate, $endDate, $username)
    {
        $airlines = ['citilink', 'garuda', 'lion', 'sriwijaya', 'qgcorner', 'transnusa', 'pelni', 'airasia', 'dlu'];
        $results = [];

        foreach ($airlines as $airline) {
            if (DB::getSchemaBuilder()->hasTable($airline)) {
                $total = DB::table($airline)
                    ->where('username', $username)
                    ->whereBetween('tgl', [$startDate, $endDate])
                    ->sum('transaksi');
                
                $results[ucfirst($airline)] = $total ?? 0;
            }
        }

        // Remove zero values for cleaner display
        $results = array_filter($results, function($value) {
            return $value > 0;
        });

        return $results;
    }

    private function getDataNta($startDate, $endDate, $username)
    {
        $airlines = ['citilink', 'garuda', 'lion', 'sriwijaya', 'qgcorner', 'transnusa', 'pelni', 'airasia', 'dlu'];
        $results = [];

        foreach ($airlines as $airline) {
            if (DB::getSchemaBuilder()->hasTable($airline)) {
                $total = DB::table($airline)
                    ->where('username', $username)
                    ->whereBetween('tgl', [$startDate, $endDate])
                    ->sum('top_up');
                
                $results[ucfirst($airline)] = $total ?? 0;
            }
        }

        // Remove zero values for cleaner display
        $results = array_filter($results, function($value) {
            return $value > 0;
        });

        return $results;
    }

    private function getDataRincian($startDate, $endDate, $username)
    {
        // Total Penjualan dari semua airlines
        $totalPenjualan = 0;
        $airlines = ['citilink', 'garuda', 'lion', 'sriwijaya', 'qgcorner', 'transnusa', 'pelni', 'airasia', 'dlu'];
        
        foreach ($airlines as $airline) {
            if (DB::getSchemaBuilder()->hasTable($airline)) {
                $total = DB::table($airline)
                    ->where('username', $username)
                    ->whereBetween('tgl', [$startDate, $endDate])
                    ->sum('transaksi');
                $totalPenjualan += $total ?? 0;
            }
        }

        // Total NTA dari semua airlines
        $totalNta = 0;
        foreach ($airlines as $airline) {
            if (DB::getSchemaBuilder()->hasTable($airline)) {
                $total = DB::table($airline)
                    ->where('username', $username)
                    ->whereBetween('tgl', [$startDate, $endDate])
                    ->sum('top_up');
                $totalNta += $total ?? 0;
            }
        }

        // Biaya
        $biaya = DB::table('biaya')
            ->where('username', $username)
            ->whereBetween('tgl', [$startDate, $endDate])
            ->sum('biaya') ?? 0;

        // Insentif
        $insentif = DB::table('insentif')
            ->where('username', $username)
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->sum('insentif') ?? 0;

        // Diskon (jika ada tabel tiket)
        $diskon = 0;
        if (DB::getSchemaBuilder()->hasTable('tiket')) {
            $diskon = DB::table('tiket')
                ->where('username', $username)
                ->whereBetween('tgl_issued', [$startDate, $endDate])
                ->sum('diskon') ?? 0;
        }

        // Hitung Rugi/Laba
        $rugiLaba = ($totalPenjualan - $totalNta - $diskon - $biaya) + $insentif;

        return [
            'Penjualan' => $totalPenjualan,
            'NTA' => $totalNta,
            'Diskon' => $diskon,
            'Biaya' => $biaya,
            'Insentif' => $insentif,
            'Rugi / Laba' => $rugiLaba
        ];
    }

    private function getDataPln($startDate, $endDate, $username)
    {
        $pln = 0;
        $sisaSaldo = 0;

        if (DB::getSchemaBuilder()->hasTable('pln')) {
            // Total transaksi PLN
            $pln = DB::table('pln')
                ->where('username', $username)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->sum('transaksi') ?? 0;

            // Sisa saldo (ambil saldo terakhir)
            $lastRecord = DB::table('pln')
                ->where('username', $username)
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $sisaSaldo = $lastRecord ? $lastRecord->saldo : 0;
        }

        return [
            'PLN' => $pln,
            'Sisa Saldo' => $sisaSaldo
        ];
    }

    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        
        $data = $this->getRekapData($startDate, $endDate);

        // Untuk implementasi export Excel, Anda bisa menggunakan package seperti Maatwebsite/Laravel-Excel
        // Ini adalah contoh sederhana
        
        return response()->json([
            'success' => true,
            'message' => 'Export Excel berhasil',
            'data' => $data,
            'period' => $startDate . ' - ' . $endDate
        ]);
    }
}