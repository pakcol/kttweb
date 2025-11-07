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
            'username' => 'superuser',
            'name' => 'Super Admin',
            'password' => Hash::make('super123'), // ganti dengan password yang aman
            'roles' => 'superuser',
        ]);

        // Admin default
        Account::create([
            'username' => 'admin',
            'name' => 'Admin KTT',
            'password' => Hash::make('admin123'), // ganti sesuai kebutuhan
            'roles' => 'admin',
        ]);
    }
}
