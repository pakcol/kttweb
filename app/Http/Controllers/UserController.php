<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('adduser', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'userid' => 'required|string|max:255|unique:users,userid',
            'password' => 'required|string|min:4',
            'rules' => 'required|string',
        ]);

        User::create([
            'nama' => $request->nama,
            'userid' => $request->userid,
            'password' => Hash::make($request->password),
            'rules' => $request->rules,
        ]);

        return redirect()->route('adduser.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('adduser.index')->with('success', 'User berhasil dihapus!');
    }
}
