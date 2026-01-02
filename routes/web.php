<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotaController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\TutupKasController;
use App\Http\Controllers\SubagentController;
use App\Http\Controllers\BiayaController;
use App\Http\Controllers\PpobController;
use App\Http\Controllers\BukuBankController; 
use App\Http\Controllers\InsentifController;

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

    // ======== HALAMAN TAMBAHAN ========
    Route::view('/sub-agent', 'sub-agent')->name('sub-agent');
    Route::view('/pln', 'pln')->name('pln');
    Route::view('/admin', 'admin')->middleware('superuser')->name('admin');

    // ========  BUKU BANK CONTROLLER ========
    Route::middleware('superuser')->group(function () {
        Route::get('/buku-bank', [BukuBankController::class, 'index'])->name('buku-bank.index');
        Route::post('/buku-bank/topup', [BukuBankController::class, 'topUp'])->name('buku-bank.topup');

        // ======== REKAP PENJUALAN CONTROLLER ========
        Route::get('/rekap-penjualan', [NotaController::class, 'rekap'])
            ->name('rekap.penjualan');
    });

    // ======== TIKET CONTROLLER  ========
    Route::prefix('tiket')->name('tiket.')->group(function () {
        Route::get('/', [TiketController::class, 'index'])->name('index'); 
        Route::post('/store', [TiketController::class, 'store'])->name('store'); 
    });

    // ======== NOTA, PIUTANG CONTROLLER ========
    Route::get('/nota/by-tiket/{kodeBooking}', 
        [NotaController::class, 'showByKodeBooking']
    );
    Route::get('/piutang', [NotaController::class, 'piutangTiket'])
        ->name('piutang.index');
    Route::put('/nota/piutang/update', [NotaController::class, 'updatePiutang'])
        ->name('nota.updatePiutang');


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
        Route::put('/{kode_booking}', [TiketController::class, 'update'])->name('update');
        Route::delete('/{kode_booking}', [TiketController::class, 'destroy'])->name('destroy');
    });

    // ======== TUTUP KAS ========
    Route::get('/tutup-kas', [TutupKasController::class, 'index'])->name('tutup-kas');
    Route::post('/tutup-kas', [TutupKasController::class, 'store'])->name('tutup-kas.store');
    Route::view('/tutupKas', 'tutup-kas')->name('tutupKas');
    Route::get('/tutup-kas/search', [TutupKasController::class, 'search'])->name('tutup-kas.search');

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
    Route::resource('ppob', PpobController::class)->only([
        'index', 'store', 'update', 'destroy'
    ]);
    Route::get('/ppobPiutang', [PpobController::class, 'ppobPiutang'])
    ->name('ppob.piutang');

    // ======== FIND TICKET CONTROLLER ======== 
    Route::prefix('find-ticket')->name('find.')->group(function () {
        Route::get('/', [TiketController::class, 'indexFind'])->name('indexFind');
        Route::get('/search', [TiketController::class, 'searchTiket'])->name('searchTiket');
    });

    // ======== MUTASI TIKET CONTROLLER ======== 
    Route::get('/mutasi-tiket', [TiketController::class, 'indexMutasi'])->name('mutasi-tiket.index');
    Route::post('/mutasi-tiket', [TiketController::class, 'topupMutasi'])->name('mutasi-tiket.topup');

    // ======== ADD USER ======== 
    Route::middleware('superuser')->group(function () {
         Route::view('/cash-flow', 'cash-flow')->name('cash-flow');
        Route::get('/register', [UserController::class, 'create'])->name('register.create'); // Menggunakan method create
        Route::post('/register', [UserController::class, 'store'])->name('register.store');
        Route::delete('/register/{username}', [UserController::class, 'destroy'])->name('register.destroy');
        Route::get('/insentif', [InsentifController::class, 'index'])
            ->name('insentif.index');

        Route::post('/insentif', [InsentifController::class, 'store'])
            ->name('insentif.store');

    });

    // ======== LOGOUT ========
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});