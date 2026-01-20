<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SubagentController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\PpobController;
use App\Http\Controllers\BukuBankController; 
use App\Http\Controllers\InsentifController;
use App\Http\Controllers\MutasiTiketController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('homeDb');
    }
    return view('home');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {

    // ======== DASHBOARD ========
    Route::get('/homeDb', function () {
        return view('homeDb');
    })->name('homeDb');

    // ========  BUKU BANK CONTROLLER ========
    Route::middleware('superuser')->group(function () {
        Route::get('/buku-bank', [BukuBankController::class, 'index'])->name('buku-bank.index');
        Route::post('/buku-bank/setor', [BukuBankController::class, 'setor'])->name('buku-bank.setor');

    });
    // ======== TIKET CONTROLLER  ========
    Route::prefix('tiket')->name('tiket.')->group(function () {
        Route::get('/', [TiketController::class, 'index'])->name('index'); 
        Route::post('/store', [TiketController::class, 'store'])->name('store'); 
        Route::get('by-tiket/{kode}', [TiketController::class, 'byTiket'])
            ->name('by-tiket');

        // INDEX PIUTANG
        Route::get('/piutang', [TiketController::class, 'indexPiutang'])
            ->name('piutang');

        // ✅ UPDATE / REALISASI PIUTANG
        Route::put('/piutang/{id}', [TiketController::class, 'updatePiutang'])
            ->name('piutang.update');
    });

    // ======== INVOICE CONTROLLER ========
    Route::get('/invoice/{kode_booking}', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice-multi', [InvoiceController::class, 'showMulti'])->name('invoice.multi');
    Route::post('/invoice/{id}/update-materai', [InvoiceController::class, 'updateMaterai'])->name('invoice.updateMaterai');

    // ======== INPUT TIKET CONTROLLER ========
    Route::prefix('input-tiket')->name('input-tiket.')->group(function () {
        Route::get('/', [TiketController::class, 'index'])->name('index');       
        Route::post('/', [TiketController::class, 'store'])->name('store');      
        Route::get('/search', [TiketController::class, 'search'])->name('search');
        Route::get('/{kode_booking}', [TiketController::class, 'getTiket'])->name('get');
        Route::post('/{kode_booking}', [TiketController::class, 'update'])->name('update');
        Route::delete('/{kode_booking}', [TiketController::class, 'destroy'])->name('destroy');
    });

    // ======== SUBAGENT CONTROLLER ======== 
    Route::prefix('subagent')->name('subagent.')->group(function () {
        Route::get('/', [SubagentController::class, 'index'])->name('index');
        Route::post('/topup',[SubagentController::class, 'topup'])->name('topup');
        Route::get('/search', [SubagentController::class, 'search'])->name('search');
        Route::get('/{id}', [SubagentController::class, 'show'])->name('show');
        Route::put('/{id}', [SubagentController::class, 'update'])->name('update');
        Route::delete('/{id}', [SubagentController::class, 'destroy'])->name('destroy');
        Route::get('/export', [SubagentController::class, 'exportExcel'])->name('export');
    });

    // ======== BIAYA CONTROLLER ======== 
    Route::get('/biaya', [BiayaController::class, 'index'])->name('biaya.index');
    Route::post('/biaya', [BiayaController::class, 'store'])->name('biaya.store');

    // ======== PPOB CONTROLLER ======== 
    // ======== PPOB CONTROLLER ========
Route::prefix('ppob')->name('ppob.')->group(function () {

    // Halaman utama PPOB
    Route::get('/', [PpobController::class, 'index'])
        ->name('index');

    // Simpan data PPOB baru
    Route::post('/', [PpobController::class, 'store'])
        ->name('store');

    // Top up PPOB (jenis_ppob_id = 5, id_pel = 0)
    Route::post('/topup', [PpobController::class, 'topup'])
        ->name('topup');

    // ✅ UPDATE / EDIT PPOB
    Route::put('/{id}', [PpobController::class, 'update'])
        ->name('update');

    // Halaman piutang PPOB
    Route::get('/piutang', [PpobController::class, 'ppobPiutang'])
        ->name('piutang');

    // Bayar / realisasi piutang
    Route::put('/piutang/{id}', [PpobController::class, 'updatePiutang'])
        ->name('piutang.update');
    Route::get('/piutang/search', [TiketController::class, 'searchPiutang']);


    // Hapus data PPOB
    Route::delete('/{id}', [PpobController::class, 'destroy'])
        ->name('destroy');
});



    // ======== FIND TICKET CONTROLLER ======== 
    Route::prefix('find-ticket')->name('find.')->group(function () {
        Route::get('/', [TiketController::class, 'indexFind'])->name('indexFind');
        Route::get('/search', [TiketController::class, 'searchTiket'])->name('searchTiket');
    });

    // ======== MUTASI TIKET CONTROLLER ======== 
    Route::get('/mutasi-tiket', [MutasiTiketController::class, 'index'])->name('mutasi-tiket.index');
    Route::post('/mutasi-tiket', [TiketController::class, 'topupMutasi'])->name('mutasi-tiket.topup');
    Route::put(
        '/mutasi-tiket/piutang/{id}',
        [MutasiTiketController::class, 'updatePiutang']
    )->name('mutasi-tiket.updatePiutang');


    // ======== CASH FLOW CONTROLLER ========
    Route::get('/cash-flow', [MutasiTiketController::class, 'cashFlow'])
        ->name('cash-flow.cashFlow');
    // simpan setoran tutup kas
    Route::post('/cash-flow/tutup-kas', [BukuBankController::class, 'store'])
        ->name('cashflow.store');

    // ======== REKAP PENJUALAN ========
    Route::get('/rekap-penjualan', [MutasiTiketController::class, 'rekapPenjualan'])
        ->name('rekap-penjualan.index');
    Route::get('/rekap-penjualan/export', [MutasiTiketController::class, 'exportRekapPenjualan'])
    ->name('rekap-penjualan.export');

    // ======== ADD USER ======== 
    Route::middleware('superuser')->group(function () {
        Route::get('/register', [UserController::class, 'create'])->name('register.create'); // Menggunakan method create
        Route::post('/register', [UserController::class, 'store'])->name('register.store');
        Route::delete('/register/{username}', [UserController::class, 'destroy'])->name('register.destroy');

    });

    // ======== LOGOUT ========
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});