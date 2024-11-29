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
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(), // Added line
            ],
            // ...other seed data...
        ]);
    }
}