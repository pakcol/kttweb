<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'username' => 'superuser',
            'name'     => 'Super Admin',
            'password' => Hash::make('super123'),
            'roles'    => 'superuser',
        ]);

        User::create([
            'username' => 'admin',
            'name'     => 'Admin KTT',
            'password' => Hash::make('admin123'),
            'roles'    => 'admin',
        ]);
    }
}
