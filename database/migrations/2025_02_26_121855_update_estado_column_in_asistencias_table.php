<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero modificamos la columna para que acepte el nuevo valor
        DB::statement("ALTER TABLE asistencias MODIFY COLUMN estado ENUM('presente', 'ausente', 'tardanza', 'fuga') DEFAULT 'presente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertimos a los valores originales
        DB::statement("ALTER TABLE asistencias MODIFY COLUMN estado ENUM('presente', 'ausente', 'tardanza') DEFAULT 'presente'");
    }
}; 