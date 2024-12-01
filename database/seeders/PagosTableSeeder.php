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
                'valor_pendiente' => 50.00,
                'fecha_proximo_pago' => '2025-01-01',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more records as needed
        ]);
    }
}