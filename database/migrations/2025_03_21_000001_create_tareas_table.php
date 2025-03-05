<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->datetime('fecha_limite');
            $table->integer('puntos_maximos');
            $table->json('archivos')->nullable();
            $table->json('imagenes')->nullable();
            $table->foreignId('aula_virtual_id')->constrained('aulas_virtuales')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tareas');
    }
}; 