<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cuestionarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_virtual_id')->constrained('aulas_virtuales')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion')->nullable();
            $table->integer('tiempo_limite')->comment('Tiempo en minutos');
            $table->integer('intentos_permitidos')->default(1);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuestionario_id')->constrained()->onDelete('cascade');
            $table->text('pregunta');
            $table->enum('tipo', ['opcion_multiple', 'verdadero_falso']);
            $table->timestamps();
        });

        Schema::create('opciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pregunta_id')->constrained()->onDelete('cascade');
            $table->text('texto');
            $table->boolean('es_correcta');
            $table->timestamps();
        });

        Schema::create('intentos_cuestionario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuestionario_id')->constrained()->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('inicio');
            $table->timestamp('fin')->nullable();
            $table->decimal('calificacion', 5, 2)->nullable();
            $table->integer('numero_intento');
            $table->timestamps();
        });

        Schema::create('respuestas_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intento_id')->constrained('intentos_cuestionario')->onDelete('cascade');
            $table->foreignId('pregunta_id')->constrained()->onDelete('cascade');
            $table->foreignId('opcion_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('respuestas_usuario');
        Schema::dropIfExists('intentos_cuestionario');
        Schema::dropIfExists('opciones');
        Schema::dropIfExists('preguntas');
        Schema::dropIfExists('cuestionarios');
    }
}; 