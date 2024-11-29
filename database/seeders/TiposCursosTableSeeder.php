<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposCursosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipos_cursos')->insert([
            ['nombre' => 'Online', 'descripcion' => 'Cursos en línea', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Presencial', 'descripcion' => 'Cursos presenciales', 'created_at' => now(), 'updated_at' => now()],
            // ...other seed data...
        ]);
    }
}