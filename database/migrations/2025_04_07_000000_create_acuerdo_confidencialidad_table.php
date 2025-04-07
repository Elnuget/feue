<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('acuerdo_confidencialidad', function (Blueprint $table) {
            $table->id();
            $table->enum('estado', ['Pendiente', 'Entregado'])->default('Pendiente');
            $table->string('acuerdo')->comment('Ruta donde se guarda el archivo PDF');
            $table->foreignId('curso_id')->constrained('cursos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acuerdo_confidencialidad');
    }
}; 