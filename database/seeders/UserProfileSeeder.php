<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserProfile;
use App\Models\User;

class UserProfileSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        foreach ($users as $user) {
            UserProfile::create([
                'user_id' => $user->id,
                'phone' => '1234567890',
                'birth_date' => '1990-01-01',
                'gender' => 'Masculino',
                'photo' => 'default.jpg',
                'cedula' => '1234567890',
                'direccion_calle' => '123 Main St',
                'direccion_ciudad' => 'City',
                'direccion_provincia' => 'Province',
                'codigo_postal' => '12345',
                'numero_referencia' => 'REF123',
                'last_login_at' => now(),
            ]);
        }
    }
}