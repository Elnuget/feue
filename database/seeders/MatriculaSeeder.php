<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MatriculaSeeder extends Seeder
{
    public function run()
    {
        // Asignamos cada usuario a un curso distinto
        $matriculas = [
            1 => 1, // Carlos => Iñaquito Preuniversitario Paralelo B (170.00)
            2 => 2, // José => Iñaquito Preuniversitario Paralelo C (170.00)
            3 => 3, // María => Ajavi Preuniversitario Paralelo A (170.00)
            4 => 4, // Pedro => Virtual Preuniversitario Paralelo A (100.00)
            5 => 5, // Lucía => Iñaquito Preuniversitario Intensivo (160.00)
            6 => 6, // Andrés => Ajavi Preuniversitario Intensivo (160.00)
        ];

        // Precios de los nuevos cursos
        $precios = [
            1 => 170.00,
            2 => 170.00,
            3 => 170.00,
            4 => 100.00,
            5 => 160.00,
            6 => 160.00,
        ];

        $data = [];
        foreach ($matriculas as $usuario_id => $curso_id) {
            $precio = $precios[$curso_id];

            $data[] = [
                'usuario_id' => $usuario_id,
                'curso_id' => $curso_id,
                'fecha_matricula' => Carbon::now(),
                'monto_total' => $precio,
                'valor_pendiente' => $precio,
                'estado_matricula' => 'Aprobada',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        DB::table('matriculas')->insert($data);
    }
}
