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
                'id' => 1, // Ensure this ID exists
                'nombre' => 'Curso de Laravel',
                'descripcion' => 'Aprende a desarrollar aplicaciones web con Laravel.',
                'precio' => 199.99,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
            ],
            [
                'id' => 2, // Ensure this ID exists
                'nombre' => 'Curso de Vue.js',
                'descripcion' => 'Aprende a desarrollar aplicaciones web con Vue.js.',
                'precio' => 149.99,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
            ],
            // ...other seed data...
        ]);
    }
}