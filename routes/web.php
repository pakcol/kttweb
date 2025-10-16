<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\InputDataController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\InvoiceController;

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

    Route::view('/sub-agent', 'sub-agent')->name('sub-agent');
    Route::view('/pln', 'pln')->name('pln');
    Route::view('/admin', 'admin')->name('admin');
    Route::view('/tutupKas', 'tutupKas')->name('tutupKas');
    Route::view('/cashFlow', 'cashFlow')->name('cashFlow');
    Route::view('/rekapPenjualan', 'recapPenjualan')->name('rekapPenjualan');
    Route::view('/piutang', 'piutang')->name('piutang');

    Route::get('/piutang/summary', [PiutangController::class, 'getSummary'])->name('piutang.summary');
    Route::get('/piutang/detail/{id}', [PiutangController::class, 'getDetail'])->name('piutang.detail');
    Route::get('/piutang/export', [PiutangController::class, 'exportExcel'])->name('piutang.export');

    Route::prefix('tiket')->name('tiket.')->group(function () {
        Route::get('/', [TiketController::class, 'index'])->name('index'); 
        Route::post('/store', [TiketController::class, 'store'])->name('store'); 
    });

    Route::get('/invoice/{id}', [InvoiceController::class, 'showSingle'])->name('invoice.single');
    Route::get('/invoice-multi', [InvoiceController::class, 'showMulti'])->name('invoice.multi');
    Route::post('/invoice/{id}/update-materai', [InvoiceController::class, 'updateMaterai'])->name('invoice.updateMaterai');

    Route::prefix('input-data')->name('input-data.')->group(function () {
        Route::get('/', [InputDataController::class, 'index'])->name('index');       
        Route::post('/', [InputDataController::class, 'store'])->name('store');      
        Route::get('/search', [InputDataController::class, 'search'])->name('search');
        Route::get('/{id}', [InputDataController::class, 'getTiket'])->name('get');
        Route::put('/{id}', [InputDataController::class, 'update'])->name('update');
        Route::delete('/{id}', [InputDataController::class, 'destroy'])->name('destroy'); // DELETE
    });

    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
});

// Route GET destroy sudah tidak perlu, pakai DELETE saja
// Route::get('/input-data/destroy/{id}', [InputDataController::class, 'destroy'])->name('input-data.destroy');

Route::resource('account', AccountController::class);
