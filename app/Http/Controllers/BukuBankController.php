<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BCA;
use App\Models\BTN;
use App\Models\BNI;
use App\Models\Mandiri;
use App\Models\BRI;

class BukuBankController extends Controller
{
    public function index()
    {
        $allData = $this->getAllBankData();
        return view('bukuBank', compact('allData'));
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'tanggal' => 'required|date',
            'bank' => 'required|string',
            'debit' => 'required',
            'kredit' => 'required',
            'keterangan' => 'required|string|max:30'
        ]);

        // Format nominal - hapus semua karakter non-digit
        $debit = (float) str_replace(['.', ','], '', $request->debit);
        $kredit = (float) str_replace(['.', ','], '', $request->kredit);

        // Validasi numeric setelah cleaning
        if (!is_numeric($debit) || !is_numeric($kredit)) {
            return redirect()->back()->with('error', 'Nilai debit dan kredit harus berupa angka');
        }

        // Dapatkan username dari user yang login
        $username = Auth::user()->username ?? 'default_user';

        // Hitung saldo berdasarkan logic dari VB code
        $saldo = $this->calculateSaldo($request->bank, $debit, $kredit, $username);

        // Simpan data ke tabel sesuai bank yang dipilih
        try {
            $data = [
                'tgl' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'credit' => $kredit,
                'debit' => $debit,
                'saldo' => $saldo,
                'username' => $username
            ];

            switch ($request->bank) {
                case 'BCA':
                    BCA::create($data);
                    break;
                    
                case 'BTN':
                    BTN::create($data);
                    break;
                    
                case 'BNI':
                    BNI::create($data);
                    break;
                    
                case 'MANDIRI':
                    Mandiri::create($data);
                    break;
                    
                case 'BRI':
                    BRI::create($data);
                    break;
                    
                default:
                    return redirect()->back()->with('error', 'Bank tidak valid');
            }

            return redirect()->back()->with('success', 'Data berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    // Method untuk menghapus data
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'bank' => 'required|string'
        ]);

        $username = Auth::user()->username ?? 'default_user';

        try {
            $deleted = false;

            // Hapus data dari tabel sesuai bank yang dipilih
            switch ($request->bank) {
                case 'BCA':
                    $record = BCA::where('id', $request->id)
                                ->where('username', $username)
                                ->first();
                    if ($record) {
                        $record->delete();
                        $deleted = true;
                    }
                    break;
                    
                case 'BTN':
                    $record = BTN::where('id', $request->id)
                                ->where('username', $username)
                                ->first();
                    if ($record) {
                        $record->delete();
                        $deleted = true;
                    }
                    break;
                    
                case 'BNI':
                    $record = BNI::where('id', $request->id)
                                ->where('username', $username)
                                ->first();
                    if ($record) {
                        $record->delete();
                        $deleted = true;
                    }
                    break;
                    
                case 'MANDIRI':
                    $record = Mandiri::where('id', $request->id)
                                    ->where('username', $username)
                                    ->first();
                    if ($record) {
                        $record->delete();
                        $deleted = true;
                    }
                    break;
                    
                case 'BRI':
                    $record = BRI::where('id', $request->id)
                                ->where('username', $username)
                                ->first();
                    if ($record) {
                        $record->delete();
                        $deleted = true;
                    }
                    break;
                    
                default:
                    return redirect()->back()->with('error', 'Bank tidak valid');
            }

            if ($deleted) {
                // Update saldo untuk record setelah data yang dihapus
                $this->updateSubsequentSaldo($request->bank, $username);
                
                return redirect()->back()->with('success', 'Data berhasil dihapus!');
            } else {
                return redirect()->back()->with('error', 'Data tidak ditemukan atau tidak memiliki akses');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // Method untuk menghapus multiple data
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
            'bank' => 'required|string'
        ]);

        $username = Auth::user()->username ?? 'default_user';

        try {
            $deletedCount = 0;

            switch ($request->bank) {
                case 'BCA':
                    $deletedCount = BCA::whereIn('id', $request->ids)
                                      ->where('username', $username)
                                      ->delete();
                    break;
                    
                case 'BTN':
                    $deletedCount = BTN::whereIn('id', $request->ids)
                                      ->where('username', $username)
                                      ->delete();
                    break;
                    
                case 'BNI':
                    $deletedCount = BNI::whereIn('id', $request->ids)
                                      ->where('username', $username)
                                      ->delete();
                    break;
                    
                case 'MANDIRI':
                    $deletedCount = Mandiri::whereIn('id', $request->ids)
                                          ->where('username', $username)
                                          ->delete();
                    break;
                    
                case 'BRI':
                    $deletedCount = BRI::whereIn('id', $request->ids)
                                      ->where('username', $username)
                                      ->delete();
                    break;
                    
                default:
                    return response()->json(['success' => false, 'message' => 'Bank tidak valid']);
            }

            if ($deletedCount > 0) {
                // Update saldo untuk record setelah data yang dihapus
                $this->updateSubsequentSaldo($request->bank, $username);
                
                return response()->json([
                    'success' => true, 
                    'message' => $deletedCount . ' data berhasil dihapus!'
                ]);
            } else {
                return response()->json([
                    'success' => false, 
                    'message' => 'Tidak ada data yang berhasil dihapus'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk update saldo record setelah data yang dihapus
    private function updateSubsequentSaldo($bank, $username)
    {
        $tableName = strtolower($bank);
        
        // Ambil semua data setelah penghapusan, diurutkan berdasarkan tanggal dan ID
        $records = DB::table($tableName)
            ->where('username', $username)
            ->orderBy('tgl', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $runningSaldo = 0;
        
        // Update saldo untuk setiap record
        foreach ($records as $record) {
            // Hitung saldo baru sesuai formula: saldo_sebelumnya - debit + kredit
            $newSaldo = round($runningSaldo - $record->debit + $record->credit, 2);
            
            // Update saldo record
            DB::table($tableName)
                ->where('id', $record->id)
                ->update(['saldo' => $newSaldo]);
            
            $runningSaldo = $newSaldo;
        }
    }

    // Method untuk menghitung saldo sesuai logic VB
    private function calculateSaldo($bank, $debit, $kredit, $username)
    {
        // Ambil saldo terakhir dari bank yang dipilih (DATANILAIBANK dalam VB)
        $lastSaldo = $this->getLastSaldo($bank, $username);
        
        // Hitung saldo baru sesuai formula VB: SISASALDO = DATANILAIBANK - Debit + Kredit
        $newSaldo = round($lastSaldo - $debit + $kredit, 2);
        
        return $newSaldo;
    }

    // Method untuk mendapatkan saldo terakhir (DATANILAIBANK dalam VB)
    private function getLastSaldo($bank, $username)
    {
        $tableName = strtolower($bank);
        
        // Query untuk mendapatkan saldo terakhir berdasarkan username
        $lastRecord = DB::table($tableName)
            ->where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->first();
        
        // Jika ada record sebelumnya, gunakan saldo dari record tersebut
        // Jika tidak ada, saldo awal = 0
        return $lastRecord ? $lastRecord->saldo : 0;
    }

    // Method untuk mengambil semua data dari semua bank
    private function getAllBankData()
    {
        $username = Auth::user()->username ?? 'default_user';

        $bcaData = BCA::where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->bank = 'BCA';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $btnData = BTN::where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->bank = 'BTN';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $bniData = BNI::where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->bank = 'BNI';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $mandiriData = Mandiri::where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->bank = 'MANDIRI';
                $item->tanggal = $item->tgl;
                return $item;
            });
        
        $briData = BRI::where('username', $username)
            ->orderBy('tgl', 'desc')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function($item) {
                $item->bank = 'BRI';
                $item->tanggal = $item->tgl;
                return $item;
            });

        // Gabungkan semua data dan urutkan berdasarkan tanggal dan ID
        return $bcaData->concat($btnData)
                      ->concat($bniData)
                      ->concat($mandiriData)
                      ->concat($briData)
                      ->sortByDesc('tgl')
                      ->sortByDesc('id');
    }
}