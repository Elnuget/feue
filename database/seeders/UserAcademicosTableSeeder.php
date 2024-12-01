<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserAcademicosTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('user_academicos')->insert([
            [
                'user_id' => 1,
                'estado_academico_id' => 1,
                'acta_grado' => 'Acta 001',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'estado_academico_id' => 2,
                'acta_grado' => 'Acta 002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // ...other seed data...
        ]);
    }
}