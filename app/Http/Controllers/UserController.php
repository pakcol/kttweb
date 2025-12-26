<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * List user
     */
    public function index()
    {
        $users = User::all();
        return view('register.index', compact('users'));
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function create()
    {
        return view('register'); // Mengarah ke view addaccount.blade.php
    }

    public function destroy($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        
        // Cegah penghapusan diri sendiri
        if (Auth::user()->username === $username) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus akun sendiri');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }

    /**
     * Store user baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'name'     => 'required|string',
            'password' => 'required|min:6',
            'roles'    => 'required|in:superuser,admin',
        ]);

        User::create([
            'username' => $request->username,
            'name'     => $request->name,
            'password' => Hash::make($request->password),
            'roles'    => $request->roles,
        ]);

        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Login manual pakai username
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/homeDb');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
