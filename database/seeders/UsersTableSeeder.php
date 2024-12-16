<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Insertamos los usuarios
        DB::table('users')->insert([
            [
                'name' => 'Carlos',
                'email' => 'cangulo009@outlook.es',
                'password' => Hash::make('carlosangulo1234'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ricardo',
                'email' => 'ricarhidalgo2020@gmail.com',
                'password' => Hash::make('ricardohidalgo1234'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'José',
                'email' => 'jose@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'María',
                'email' => 'maria@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Pedro',
                'email' => 'pedro@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Lucía',
                'email' => 'lucia@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Andrés',
                'email' => 'andres@example.com',
                'password' => Hash::make('password123'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
            ],
        ]);

        // Obtenemos todos los usuarios
        $users = DB::table('users')->get();
        // Obtenemos los cursos para extraer sus horarios
        $cursos = DB::table('cursos')->get();

        foreach ($users as $user) {
            // Asignar rol (Carlos y Ricardo administradores, resto usuarios)
            DB::table('model_has_roles')->insert([
                'role_id' => ($user->email === 'cangulo009@outlook.es' || $user->email === 'ricarhidalgo2020@gmail.com') ? 1 : 2,
                'model_type' => 'App\Models\User',
                'model_id' => $user->id,
            ]);

            // Seleccionar un curso aleatorio
            $curso = $cursos->random();
            $horario = $curso->horario;

            // Ejemplo de horario: "Lunes a Viernes, 9:00 AM - 12:00 PM"
            // Paso 1: Separar por coma para obtener la parte de horas
            $partes = explode(',', $horario);
            // $partes[0] = "Lunes a Viernes"
            // $partes[1] = " 9:00 AM - 12:00 PM"

            if (count($partes) > 1) {
                $horas = trim($partes[1]); // "9:00 AM - 12:00 PM"

                // Separar por el guion
                $rango = explode('-', $horas); 
                // $rango[0] = "9:00 AM"
                // $rango[1] = " 12:00 PM"

                $hora_inicio = trim($rango[0]); // "9:00 AM"

                // Convertir "9:00 AM" a formato 24 horas
                // Asumiremos formato h:ii AM/PM
                $fecha_actual = now()->format('Y-m-d');
                $hora_24 = $this->convertirHora24($hora_inicio);

                $fecha_hora_asistencia = $fecha_actual . ' ' . $hora_24;
            } else {
                // Si no se puede parsear el horario, usar la hora actual
                $fecha_hora_asistencia = now();
            }

            // Insertar asistencia
            DB::table('asistencias')->insert([
                'user_id' => $user->id,
                'fecha_hora' => $fecha_hora_asistencia,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Convierte una hora con formato "H:MM AM/PM" a "H:MM" (24h).
     * Asume que el formato se cumple correctamente.
     */
    private function convertirHora24($hora12)
    {
        // Ej: "9:00 AM"
        $partes = explode(' ', $hora12);
        $hora_min = $partes[0]; // "9:00"
        $am_pm = strtoupper($partes[1]); // "AM" o "PM"

        list($hora, $min) = explode(':', $hora_min);

        $hora = (int)$hora; 
        $min = (int)$min;

        if ($am_pm === 'PM' && $hora !== 12) {
            $hora += 12;
        } elseif ($am_pm === 'AM' && $hora === 12) {
            $hora = 0;
        }

        return sprintf('%02d:%02d:00', $hora, $min);
    }
}
