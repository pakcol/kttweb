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
            'username' => 'required|string|max:255|unique:accounts,username',
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:4',
            'roles' => 'required|string',
        ]);

        Account::create([
            'username' => $request->username,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'roles' => $request->roles,
            //'password' => $request->password,
        ]);

        return redirect()->route('addaccount.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy($username)
    {
        $account = Account::findOrFail($username);
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