<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BankSeeder extends Seeder
{
    public function run(): void
    {
        $banks = [
            'BRI',
            'BNI',
            'BCA',
            'MANDIRI',
            'BTN',
        ];

        foreach ($banks as $name) {
            DB::table('bank')->updateOrInsert(
                ['name' => $name],
                [
                    'saldo'      => 0,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
