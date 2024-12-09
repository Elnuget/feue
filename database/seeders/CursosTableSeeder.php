<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursosTableSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Eliminate existing courses
        DB::table('cursos')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insert new courses
        DB::table('cursos')->insert([
            [
                'nombre' => 'CURSO COMPLETO INGRESA A LA U 2025 (3RO BACHILLERATO)',
                'descripcion' => 'DICIEMBRE 2024 - NOVIEMBRE 2025\nACOMPAÑAMIENTO HASTA LA MATRICULACIÓN EN LA U',
                'precio' => 389.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
            ],
            [
                'nombre' => 'CURSO COMPLETO INGRESA A LA U 2025',
                'descripcion' => 'DICIEMBRE - MAYO\nACOMPAÑAMIENTO HASTA LA MATRICULACIÓN EN LA U\n0998436160',
                'precio' => 170.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
            ],
            [
                'nombre' => 'CURSO COMPLETO INGRESA A LA U 2025 VIRTUAL',
                'descripcion' => 'DICIEMBRE - MAYO\nACOMPAÑAMIENTO HASTA LA MATRICULACIÓN EN LA U\n0998436160',
                'precio' => 100.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
            ],
        ]);
    }
}