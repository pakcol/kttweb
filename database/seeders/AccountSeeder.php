<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        // Superuser default
        Account::create([
            'name' => 'Super Admin',
            'username' => 'superuser',
            'role' => 'superuser',
            'password' => Hash::make('super123'), // ganti dengan password yang aman
        ]);

        // Admin default
        Account::create([
            'name' => 'Admin KTT',
            'username' => 'admin',
            'role' => 'admin',
            'password' => Hash::make('admin123'), // ganti sesuai kebutuhan
        ]);
    }
}
