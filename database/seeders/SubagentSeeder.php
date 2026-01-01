<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subagent;

class SubagentSeeder extends Seeder
{
    public function run(): void
    {
        Subagent::firstOrCreate(
            ['nama' => 'EVI'],
            ['saldo' => 0]
        );
    }
}
