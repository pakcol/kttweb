<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;

class TutupKasController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $data = null;

        if ($tanggal) {
            $data = Penjualan::whereDate('tanggal', $tanggal)->first();
        }

        return view('tutup-kas', compact('data'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            '*.numeric' => 'nullable|numeric',
        ]);

        $penjualan = new Penjualan();
        $penjualan->tanggal = now();
        $penjualan->jam = now();

        // ===== PENJUALAN =====
        $penjualan->TTL_PENJUALAN = $request->TTL_PENJUALAN ?? 0;
        $penjualan->PIUTANG = $request->PIUTANG ?? 0;
        $penjualan->BIAYA = $request->BIAYA ?? 0;
        $penjualan->REFUND = $request->REFUND ?? 0;

        // ===== TRANSFER =====
        $penjualan->TRF_BCA = $request->TRF_BCA ?? 0;
        $penjualan->TRF_BRI = $request->TRF_BRI ?? 0;
        $penjualan->TRF_BNI = $request->TRF_BNI ?? 0;
        $penjualan->TRF_BTN = $request->TRF_BTN ?? 0;
        $penjualan->TRF_MDR = $request->TRF_MDR ?? 0;

        // ===== SETORAN =====
        $penjualan->STR_BCA = $request->STR_BCA ?? 0;
        $penjualan->STR_BRI = $request->STR_BRI ?? 0;
        $penjualan->STR_BNI = $request->STR_BNI ?? 0;
        $penjualan->STR_MDR = $request->STR_MDR ?? 0;

        // ===== SALDO AIRLINES =====
        $penjualan->SOCITILINK = $request->SOCITILINK ?? 0;
        $penjualan->SOGARUDA = $request->SOGARUDA ?? 0;
        $penjualan->SOQGCORNER = $request->SOQGCORNER ?? 0;
        $penjualan->SOLION = $request->SOLION ?? 0;
        $penjualan->SOSRIWIJAYA = $request->SOSRIWIJAYA ?? 0;
        $penjualan->SOTRANSNUSA = $request->SOTRANSNUSA ?? 0;
        $penjualan->SOPELNI = $request->SOPELNI ?? 0;
        $penjualan->SOAIRASIA = $request->SOAIRASIA ?? 0;
        $penjualan->SODLU = $request->SODLU ?? 0;

        // ===== TOP UP AIRLINES =====
        $penjualan->TUCITILINK = $request->TUCITILINK ?? 0;
        $penjualan->TUGARUDA = $request->TUGARUDA ?? 0;
        $penjualan->TUQGCORNER = $request->TUQGCORNER ?? 0;
        $penjualan->TULION = $request->TULION ?? 0;
        $penjualan->TUSRIWIJAYA = $request->TUSRIWIJAYA ?? 0;
        $penjualan->TUTRANSNUSA = $request->TUTRANSNUSA ?? 0;
        $penjualan->TUPELNI = $request->TUPELNI ?? 0;
        $penjualan->TUAIRASIA = $request->TUAIRASIA ?? 0;
        $penjualan->TUDLU = $request->TUDLU ?? 0;

        // ===== SALDO SUB AGENT =====
        $penjualan->SOEVI = $request->SOEVI ?? 0;
        $penjualan->CASH_FLOW = $request->CASH_FLOW ?? 0;

        // ===== PLN =====
        $penjualan->PLN = $request->PLN ?? 0;
        $penjualan->SALDOPLN = $request->SALDOPLN ?? 0;

        // ===== USERNAME =====
        $penjualan->username = auth()->user()->username ?? auth()->user()->name ?? 'Super Admin';

        $penjualan->save();

        return redirect()->route('tutup-kas')->with('success', 'Data Tutup Kas berhasil disimpan.');
    }

    public function search(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $data = Penjualan::whereDate('tanggal', $tanggal)->first();

        if (!$data) {
            return redirect()->route('tutup-kas')->with('error', 'Data untuk tanggal tersebut tidak ditemukan.');
        }

        return view('tutup-kas', compact('data'));
    }
}
