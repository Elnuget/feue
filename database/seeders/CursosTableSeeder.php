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
            // Cursos Preuniversitarios
            [
                'nombre' => 'Iñaquito Preuniversitario Paralelo B (Lunes a Viernes, 9:00 AM - 12:00 PM)',
                'descripcion' => 'Preuniversitario para ingreso a la U',
                'precio' => 170.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1, // Preuniversitario
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 9:00 AM - 12:00 PM',
            ],
            [
                'nombre' => 'Iñaquito Preuniversitario Paralelo C (Lunes a Viernes, 9:00 AM - 12:00 PM)',
                'descripcion' => 'Preuniversitario para ingreso a la U',
                'precio' => 170.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 9:00 AM - 12:00 PM',
            ],
            [
                'nombre' => 'Ajavi Preuniversitario Paralelo A (Lunes a Viernes, 9:00 AM - 12:00 PM)',
                'descripcion' => 'Preuniversitario para ingreso a la U',
                'precio' => 170.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 9:00 AM - 12:00 PM',
            ],
            [
                'nombre' => 'Virtual Preuniversitario Paralelo A (Lunes a Viernes, 6:00 PM - 8:00 PM)',
                'descripcion' => 'Preuniversitario para ingreso a la U (Virtual)',
                'precio' => 100.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 6:00 PM - 8:00 PM',
            ],
            [
                'nombre' => 'Iñaquito Preuniversitario Intensivo (Sábados, 9:00 AM - 4:00 PM)',
                'descripcion' => 'Preuniversitario intensivo para ingreso a la U',
                'precio' => 160.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Sábados, 9:00 AM - 4:00 PM',
            ],
            [
                'nombre' => 'Ajavi Preuniversitario Intensivo (Sábados, 9:00 AM - 4:00 PM)',
                'descripcion' => 'Preuniversitario intensivo para ingreso a la U',
                'precio' => 160.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Sábados, 9:00 AM - 4:00 PM',
            ],

            // Cursos de Enfermería
            [
                'nombre' => 'Iñaquito Enfermería (Lunes a Viernes, 7:00 AM - 9:00 AM)',
                'descripcion' => 'Curso de enfermería de 7 a 9 AM, lunes a viernes',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2, // Enfermería
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 7:00 AM - 9:00 AM',
            ],
            [
                'nombre' => 'Iñaquito Enfermería (Lunes a Viernes, 13:00 PM - 15:00 PM)',
                'descripcion' => 'Curso de enfermería de 13 a 15, lunes a viernes',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 13:00 PM - 15:00 PM',
            ],
            [
                'nombre' => 'Iñaquito Enfermería (Lunes a Viernes, 15:00 PM - 17:00 PM)',
                'descripcion' => 'Curso de enfermería de 15 a 17, lunes a viernes',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 15:00 PM - 17:00 PM',
            ],
            [
                'nombre' => 'Iñaquito Enfermería (Lunes a Viernes, 17:00 PM - 19:00 PM)',
                'descripcion' => 'Curso de enfermería de 17 a 19, lunes a viernes',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 17:00 PM - 19:00 PM',
            ],
            [
                'nombre' => 'Iñaquito Enfermería Intensivo (Sábados, 9:00 AM - 4:00 PM)',
                'descripcion' => 'Curso de enfermería intensivo',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Sábados, 9:00 AM - 4:00 PM',
            ],
            [
                'nombre' => 'Ajavi Enfermería (Lunes a Viernes, 7:00 AM - 9:00 AM)',
                'descripcion' => 'Curso de enfermería de 7 a 9 AM, lunes a viernes',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 7:00 AM - 9:00 AM',
            ],
            [
                'nombre' => 'Ajavi Enfermería (Lunes a Viernes, 12:00 PM - 14:00 PM)',
                'descripcion' => 'Curso de enfermería de 12 a 14, lunes a viernes',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 12:00 PM - 14:00 PM',
            ],
            [
                'nombre' => 'Ajavi Enfermería (Lunes a Viernes, 14:00 PM - 16:00 PM)',
                'descripcion' => 'Curso de enfermería de 14 a 16, lunes a viernes',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 14:00 PM - 16:00 PM',
            ],
            [
                'nombre' => 'Ajavi Enfermería (Lunes a Viernes, 16:00 PM - 18:00 PM)',
                'descripcion' => 'Curso de enfermería de 16 a 18, lunes a viernes',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Lunes a Viernes, 16:00 PM - 18:00 PM',
            ],
            [
                'nombre' => 'Ajavi Enfermería Intensivo (Sábados, 9:00 AM - 4:00 PM)',
                'descripcion' => 'Curso de enfermería intensivo',
                'precio' => 200.00,
                'estado' => 'Activo',
                'tipo_curso_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
                'imagen' => 'default.jpg',
                'horario' => 'Sábados, 9:00 AM - 4:00 PM',
            ],
        ]);
    }
}
