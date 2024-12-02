<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Carlos',
                'email' => 'cangulo009@outlook.es',
                'password' => Hash::make('faplol13'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'User 2',
                'email' => 'user2@example.com',
                'password' => Hash::make('password2'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            // ...other seed data...
        ]);

        $users = DB::table('users')->get();

        foreach ($users as $user) {
            DB::table('model_has_roles')->insert([
                'role_id' => $user->email === 'cangulo009@outlook.es' ? 1 : 2,
                'model_type' => 'App\Models\User',
                'model_id' => $user->id,
            ]);
        }
    }
}