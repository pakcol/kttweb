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
    
    Route::get('/homeDb', function () {
        return view('homeDb');
    })->name('homeDb');

        // ======== HALAMAN ========
    Route::view('/sub-agent', 'sub-agent')->name('sub-agent');
    Route::view('/pln', 'pln')->name('pln');
    Route::view('/admin', 'admin')->name('admin');
    Route::view('/cash-flow', 'cash-flow')->name('cash-flow');
    Route::view('/rekapPenjualan', 'recapPenjualan')->name('rekapPenjualan');
    Route::view('/insentif', 'insentif')->name('insentif');

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

    // ======== TUTUP KAS (HALAMAN + FUNGSI SIMPAN) ========
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

    // Tambahkan baris ini untuk tombol EXPORT EXCEL
    Route::get('/export', [EviController::class, 'exportExcel'])->name('export');
});

// ======== ADD USER ========
Route::get('/addaccount', [AccountController::class, 'index'])->name('addaccount.index');
Route::post('/addaccount', [AccountController::class, 'store'])->name('addaccount.store');
Route::delete('/addaccount/{id}', [AccountController::class, 'destroy'])->name('addaccount.destroy');

Route::get('/biaya', [BiayaController::class, 'index'])->name('biaya.index');
Route::post('/biaya', [BiayaController::class, 'store'])->name('biaya.store');

Route::get('/pln', [PlnController::class, 'index'])->name('pln.index');
Route::post('/pln', [PlnController::class, 'store'])->name('pln.store');

    // ======== LOGOUT ========
    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
});

// ======== ACCOUNT RESOURCE ========
Route::resource('account', AccountController::class);