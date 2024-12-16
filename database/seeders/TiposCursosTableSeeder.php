<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposCursosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('tipos_cursos')->insert([
            [
                'nombre' => 'Preuniversitario',
                'descripcion' => 'Cursos para entrar a la universidad',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Enfermería',
                'descripcion' => 'Cursos de enfermería',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
