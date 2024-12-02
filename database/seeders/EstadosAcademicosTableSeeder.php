<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosAcademicosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('estados_academicos')->insert([
            ['nombre' => 'Bachiller', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Otro', 'created_at' => now(), 'updated_at' => now()],
            // ...other seed data...
        ]);
    }
}