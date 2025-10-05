<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function showLoginForm()
    {   
        return view('login');
    }

    public function login(Request $request)
    {
        // Jika sudah login, redirect ke homeDb
        if (Auth::check()) {
            return redirect()->route('homeDb');
        }

        // Validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Coba login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect ke homeDb setelah login berhasil
            return redirect()->intended('/homeDb');
        }

        // Kalau gagal login
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}