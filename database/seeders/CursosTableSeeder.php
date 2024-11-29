<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('cursos')->insert([
            [
                'nombre' => 'Curso de Laravel',
                'descripcion' => 'Aprende a desarrollar aplicaciones web con Laravel.',
                'precio' => 199.99,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ...other seed data...
        ]);
    }
}