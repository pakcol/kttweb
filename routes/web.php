<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

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
    
    Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
});

Route::resource('account', AccountController::class);