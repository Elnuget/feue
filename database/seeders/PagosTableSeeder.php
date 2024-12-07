<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('pagos')->insert([
            [
                'matricula_id' => 1,
                'metodo_pago_id' => 1,
                'comprobante_pago' => 'comprobante1.jpg',
                'monto' => 100.00,
                'fecha_pago' => '2024-12-01',
                'estado' => 'Pendiente', // Add estado field
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'matricula_id' => 2,
                'metodo_pago_id' => 2,
                'comprobante_pago' => 'comprobante2.jpg',
                'monto' => 200.00,
                'fecha_pago' => '2024-12-02',
                'estado' => 'Aprobado', // Add estado field
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more records as needed
        ]);
    }
}