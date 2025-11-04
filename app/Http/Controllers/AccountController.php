<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::all();
        return view('addaccount', compact('accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:accounts,username',
            'role' => 'required|string',
            'password' => 'required|string|min:4',
        ]);

        Account::create([
            'name' => $request->name,
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            //'password' => $request->password,
        ]);

        return redirect()->route('addaccount.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return redirect()->route('addaccount.index')->with('success', 'User berhasil dihapus!');
    }

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