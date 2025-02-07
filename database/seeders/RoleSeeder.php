<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Crear roles
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);

        // Asignar rol admin al usuario con ID 1 (si existe)
        $adminUser = \App\Models\User::find(1);
        if ($adminUser) {
            $adminUser->assignRole('admin');
        }
    }
} 