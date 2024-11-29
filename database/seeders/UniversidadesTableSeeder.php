<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UniversidadesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('universidades')->insert([
            ['nombre' => 'Universidad Nacional', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Universidad Internacional', 'created_at' => now(), 'updated_at' => now()],
            // ...other seed data...
        ]);
    }
}