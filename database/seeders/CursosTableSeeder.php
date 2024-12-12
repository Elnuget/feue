<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursosTableSeeder extends Seeder
{
    public function run()
    {
        // Desactivar comprobación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Vaciar la tabla cursos
        DB::table('cursos')->truncate();

        // Reactivar comprobación de claves foráneas
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('cursos')->insert([
            [
                'nombre' => 'Iñaquito Preuniversitario Paralelo B',
                'descripcion' => 'Preuniversitario para ingreso a la U',
                'precio' => 170.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2, // Presencial, suponiendo que 2 es presencial
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 9:00 AM - 12:00 PM',
            ],
            [
                'nombre' => 'Iñaquito Preuniversitario Paralelo C',
                'descripcion' => 'Preuniversitario para ingreso a la U',
                'precio' => 170.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2, // Presencial
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 9:00 AM - 12:00 PM',
            ],
            [
                'nombre' => 'Ajavi Preuniversitario Paralelo A',
                'descripcion' => 'Preuniversitario para ingreso a la U',
                'precio' => 170.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2, // Presencial
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 9:00 AM - 12:00 PM',
            ],
            [
                'nombre' => 'Virtual Preuniversitario Paralelo A',
                'descripcion' => 'Preuniversitario para ingreso a la U (Virtual)',
                'precio' => 100.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1, // Online
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 6:00 PM - 8:00 PM',
            ],
            [
                'nombre' => 'Iñaquito Preuniversitario Intensivo',
                'descripcion' => 'Preuniversitario intensivo para ingreso a la U',
                'precio' => 160.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2, // Presencial
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Sábados, 9:00 AM - 4:00 PM',
            ],
            [
                'nombre' => 'Ajavi Preuniversitario Intensivo',
                'descripcion' => 'Preuniversitario intensivo para ingreso a la U',
                'precio' => 160.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2, // Presencial
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Sábados, 9:00 AM - 4:00 PM',
            ],
        ]);
    }
}
