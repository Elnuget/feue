<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Universidad;

class UniversidadesTableSeeder extends Seeder
{
    public function run()
    {
        $universidades = [
            ['nombre' => 'Universidad Nacional'],
            ['nombre' => 'Universidad Internacional'],
        ];

        foreach ($universidades as $universidad) {
            Universidad::firstOrCreate(['nombre' => $universidad['nombre']]);
        }
    }
}