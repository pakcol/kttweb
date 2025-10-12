<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\InputDataController;

// Route untuk semua user (guest dan auth) - TANPA middleware
Route::get('/', function () {
    // Manual check di sini
    if (auth()->check()) {
        return redirect()->route('homeDb');
    }
    return view('home');
});

// Route login khusus guest
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AccountController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AccountController::class, 'login']);
});

// Route yang butuh auth
Route::middleware(['auth'])->group(function () {
    Route::get('/homeDb', function () {
        return view('homeDb');
    })->name('homeDb');

    Route::get('/sub-agent', function () {
        return view('sub-agent');
    })->name('sub-agent');
    
    Route::get('/pln', function () {
        return view('pln');
    })->name('pln');
    
    Route::get('/admin', function () {
        return view('admin');
    })->name('admin');
    
    // Input Data Routes
    Route::prefix('input-data')->group(function () {
        Route::get('/', [InputDataController::class, 'index'])->name('input-data.index');
        Route::post('/', [InputDataController::class, 'store'])->name('input-data.store');
        Route::get('/search', [InputDataController::class, 'search'])->name('input-data.search');
        Route::get('/{id}', [InputDataController::class, 'getTiket'])->name('input-data.get');
        Route::put('/{id}', [InputDataController::class, 'update'])->name('input-data.update');
        Route::delete('/{id}', [InputDataController::class, 'destroy'])->name('input-data.destroy');
    });
    
    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
});

Route::resource('account', AccountController::class);