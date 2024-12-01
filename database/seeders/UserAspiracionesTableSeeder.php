<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserAspiracion;

class UserAspiracionesTableSeeder extends Seeder
{
    public function run()
    {
        $aspiraciones = [
            ['user_id' => 1, 'universidad_id' => 1],
            ['user_id' => 2, 'universidad_id' => 2],
        ];

        foreach ($aspiraciones as $aspiracion) {
            UserAspiracion::firstOrCreate($aspiracion);
        }
    }
}