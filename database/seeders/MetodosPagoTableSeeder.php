<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodosPagoTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('metodos_pago')->insert([
            ['nombre' => 'Efectivo', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Depósito', 'created_at' => now(), 'updated_at' => now()],
            // ...other seed data...
        ]);
    }
}