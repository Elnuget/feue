<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('asistencias_docentes', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Docente que registra la asistencia
            $table->unsignedBigInteger('user_id');

            // Fecha en que se marca la asistencia
            $table->date('fecha');

            // Hora de entrada (puede guardarse si el docente marca hora)
            $table->time('hora_entrada')->nullable();

            // Estado de la asistencia: Pendiente, Presente, Tarde, Ausente, etc.
            $table->enum('estado', ['Pendiente', 'Presente', 'Tarde', 'Ausente'])
                  ->default('Pendiente');

            // Relación opcional con la sesión (si existe)
            $table->unsignedBigInteger('sesion_docente_id')->nullable();

            // Observaciones: motivos de retraso, justificaciones, etc.
            $table->text('observaciones')->nullable();

            // Timestamps de Laravel (created_at, updated_at)
            $table->timestamps();

            // Claves foráneas
            // Si borras el usuario => asume 'cascade' para limpiar su asistencia
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Sesión puede no existir en el momento de la asistencia
            // onDelete('set null') => si borras la sesión, no arrasa con la asistencia
            // Podrías usar 'cascade' si lo prefieres.
            $table->foreign('sesion_docente_id')
                  ->references('id')
                  ->on('sesiones_docentes')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('asistencias_docentes');
    }
};
