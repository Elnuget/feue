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
        Schema::create('aula_virtual_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_virtual_id')->constrained('aulas_virtuales')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            // Índices para optimizar búsquedas
            $table->index(['aula_virtual_id', 'user_id']);
            
            // Índice único para evitar duplicados
            $table->unique(['aula_virtual_id', 'user_id'], 'aula_virtual_usuario_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aula_virtual_usuario');
    }
}; 