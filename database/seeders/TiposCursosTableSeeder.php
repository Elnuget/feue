<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\TiposCurso;

class TiposCursosTableSeeder extends Seeder
{
    public function run()
    {
        TiposCurso::firstOrCreate(
            ['nombre' => 'Preuniversitario'],
            [
                'descripcion' => 'Cursos para entrar a la universidad',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('tipos_cursos')->insert([
            [
                'nombre' => 'Enfermería',
                'descripcion' => 'Cursos de enfermería',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
