<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Documento;
use App\Models\User;

class DocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();

        foreach ($users as $user) {
            Documento::create([
                'user_id' => $user->id,
                'tipo_documento' => 'Foto',
                'ruta' => 'path/to/foto.jpg',
            ]);

            Documento::create([
                'user_id' => $user->id,
                'tipo_documento' => 'Acta de Grado',
                'ruta' => 'path/to/acta_de_grado.pdf',
            ]);

            Documento::create([
                'user_id' => $user->id,
                'tipo_documento' => 'Otro',
                'ruta' => 'path/to/otro_documento.docx',
            ]);
        }
    }
}