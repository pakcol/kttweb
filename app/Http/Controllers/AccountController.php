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

        if (Auth::check()) {
            return redirect()->route('homeDb');
        }

        
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            
            return redirect()->intended('/homeDb');
        }

        
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