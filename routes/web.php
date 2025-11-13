<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\InputDataController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TutupKasController;
use App\Http\Controllers\PiutangController;
use App\Http\Controllers\EviController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\PlnController;
use App\Http\Controllers\FindController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\PlnPiutangController;
use App\Http\Controllers\BukuBankController; 
use App\Http\Controllers\RekapanController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('homeDb');
    }
    return view('home');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AccountController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AccountController::class, 'login']);
});

Route::middleware(['auth'])->group(function () {

    // ======== DASHBOARD ========
    Route::get('/homeDb', function () {
        return view('homeDb');
    })->name('homeDb');

    // ======== HALAMAN TAMBAHAN ========
    Route::view('/sub-agent', 'sub-agent')->name('sub-agent');
    Route::view('/pln', 'pln')->name('pln');
    Route::view('/admin', 'admin')->middleware('superuser')->name('admin');
    Route::view('/cash-flow', 'cash-flow')->name('cash- flow');

    // ========  BUKU BANK CONTROLLER ========
    Route::middleware('superuser')->group(function () {
        Route::get('/buku-bank', [BukuBankController::class, 'index'])->name('buku-bank.index');
        Route::post('/buku-bank', [BukuBankController::class, 'store'])->name('buku-bank.store');
        Route::get('/buku-bank/search', [BukuBankController::class, 'search'])->name('buku-bank.search');

        Route::get('/rekapan-penjualan', [RekapanController::class, 'index'])->name('rekapan-penjualan.index');
        Route::delete('/buku-bank/{id}', [BukuBankController::class, 'destroy'])->name('buku-bank.destroy');
        Route::post('/buku-bank/delete-multiple', [BukuBankController::class, 'destroyMultiple'])->name('buku-bank.destroy-multiple');
    });

    // ======== PIUTANG CONTROLLER ======== 
    Route::get('/piutang', [PiutangController::class, 'index'])->name('piutang.index');
    Route::post('/piutang', [PiutangController::class, 'store'])->name('piutang.store');

    // ======== TIKET CONTROLLER ========
    Route::prefix('tiket')->name('tiket.')->group(function () {
        Route::get('/', [TiketController::class, 'index'])->name('index'); 
        Route::post('/store', [TiketController::class, 'store'])->name('store'); 
    });

    // ======== INVOICE CONTROLLER ========
    Route::get('/invoice/{id}', [InvoiceController::class, 'showSingle'])->name('invoice.single');
    Route::get('/invoice-multi', [InvoiceController::class, 'showMulti'])->name('invoice.multi');
    Route::post('/invoice/{id}/update-materai', [InvoiceController::class, 'updateMaterai'])->name('invoice.updateMaterai');

    // ======== INPUT DATA CONTROLLER ========
    Route::prefix('input-data')->name('input-data.')->group(function () {
        Route::get('/', [InputDataController::class, 'index'])->name('index');       
        Route::post('/', [InputDataController::class, 'store'])->name('store');      
        Route::get('/search', [InputDataController::class, 'search'])->name('search');
        Route::get('/{id}', [InputDataController::class, 'getTiket'])->name('get');
        Route::put('/{id}', [InputDataController::class, 'update'])->name('update');
        Route::delete('/{id}', [InputDataController::class, 'destroy'])->name('destroy');
    });

    // ======== TUTUP KAS ========
    Route::get('/tutup-kas', [TutupKasController::class, 'index'])->name('tutup-kas');
    Route::post('/tutup-kas', [TutupKasController::class, 'store'])->name('tutup-kas.store');
    Route::view('/tutupKas', 'tutup-kas')->name('tutupKas');
    Route::get('/tutup-kas/search', [TutupKasController::class, 'search'])->name('tutup-kas.search');

    // ======== EVI CONTROLLER ======== 
    Route::prefix('evi')->name('evi.')->group(function () {
        Route::get('/', [EviController::class, 'index'])->name('index');
        Route::post('/store', [EviController::class, 'store'])->name('store');
        Route::get('/search', [EviController::class, 'search'])->name('search');
        Route::get('/{id}', [EviController::class, 'show'])->name('show');
        Route::put('/{id}', [EviController::class, 'update'])->name('update');
        Route::delete('/{id}', [EviController::class, 'destroy'])->name('destroy');
        Route::get('/export', [EviController::class, 'exportExcel'])->name('export');
    });

    // ======== BIAYA CONTROLLER ======== 
    Route::get('/biaya', [BiayaController::class, 'index'])->name('biaya.index');
    Route::post('/biaya', [BiayaController::class, 'store'])->name('biaya.store');

    // ======== PLN CONTROLLER ======== 
    Route::get('/pln', [PlnController::class, 'index'])->name('pln.index');
    Route::post('/pln', [PlnController::class, 'store'])->name('pln.store');

    //piutang pln
    Route::get('/plnPiutang', [PlnController::class, 'indexPiutang'])->name('plnPiutang');
    Route::get('/plnPiutang/{id}', [PlnController::class, 'showPiutang'])->name('plnPiutang.show');
    Route::put('/plnPiutang/{id}', [PlnController::class, 'updatePiutang'])->name('plnPiutang.update');

    // ======== FIND TICKET CONTROLLER ======== 
    Route::prefix('find-ticket')->name('find.')->group(function () {
        Route::get('/', [FindController::class, 'index'])->name('index');
        Route::get('/search', [FindController::class, 'search'])->name('search');
    });

    // ======== MUTASI AIRLINES CONTROLLER ======== 
    Route::get('/mutasi-airlines', [MutasiController::class, 'index'])->name('mutasi-airlines.index');
    Route::post('/mutasi-airlines', [MutasiController::class, 'store'])->name('mutasi-airlines.store');
    Route::get('/mutasi-airlines/{airlines}', [MutasiController::class, 'show'])->name('mutasi-airlines.show');
    Route::get('/mutasi-airlines-all', [MutasiController::class, 'showAll'])->name('mutasi-airlines.all');

    // ======== ADD USER ======== 
    Route::middleware('superuser')->group(function () {
        Route::get('/addaccount', [AccountController::class, 'index'])->name('addaccount.index');
        Route::post('/addaccount', [AccountController::class, 'store'])->name('addaccount.store');
        Route::delete('/addaccount/{username}', [AccountController::class, 'destroy'])->name('addaccount.destroy');
        Route::view('/rekapPenjualan', 'recapPenjualan')->name('rekapPenjualan');
        Route::view('/insentif', 'insentif')->middleware('superuser')->name('insentif');
    });

    // ======== LOGOUT ========
    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
});

// ======== ACCOUNT RESOURCE ========
Route::resource('account', AccountController::class);
