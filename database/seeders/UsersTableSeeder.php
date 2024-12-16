<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Limpia las tablas para asegurar que no haya registros anteriores
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Insertar únicamente el primer usuario (Carlos)
        DB::table('users')->insert([
            [
                'name' => 'Carlos',
                'email' => 'cangulo009@outlook.es',
                'password' => Hash::make('carlosangulo1234'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
        ]);

        // Obtener el usuario creado
        $user = DB::table('users')->where('email', 'cangulo009@outlook.es')->first();

        // Asignar el rol de administrador al usuario (asumiendo que role_id=1 es administrador)
        DB::table('model_has_roles')->insert([
            'role_id' => 1,
            'model_type' => 'App\\Models\\User',
            'model_id' => $user->id,
        ]);

        // No se crean asistencias ni se insertan otros usuarios.
    }
}
