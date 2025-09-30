<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('home');
});

Route::get('/login', function () {
    return view('login'); 
})->name('login');

Route::resource('account', AccountController::class);

Route::get('/login', [AccountController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AccountController::class, 'login']);
Route::post('/logout', [AccountController::class, 'logout'])->name('logout');
