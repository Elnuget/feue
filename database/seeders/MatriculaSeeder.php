<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MatriculaSeeder extends Seeder
{
    public function run()
    {
        DB::table('matriculas')->insert([
            [
                'usuario_id' => 1, // Ensure this usuario_id exists in the users table
                'curso_id' => 1, // Ensure this curso_id exists in the cursos table
                'fecha_matricula' => Carbon::now(),
                'monto_total' => 100.00,
                'valor_pendiente' => 50.00,
                'estado_matricula' => 'Pendiente',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'usuario_id' => 2, // Ensure this usuario_id exists in the users table
                'curso_id' => 2, // Ensure this curso_id exists in the cursos table
                'fecha_matricula' => Carbon::now(),
                'monto_total' => 200.00,
                'valor_pendiente' => 0.00,
                'estado_matricula' => 'Aprobada',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            // ...additional seed data...
        ]);
    }
}