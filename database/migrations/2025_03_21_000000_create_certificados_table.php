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
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->string('nombre_completo');
            $table->integer('horas_curso');
            $table->string('sede_curso');
            $table->date('fecha_emision');
            $table->string('anio_emision', 2);
            $table->string('numero_certificado')->unique();
            $table->boolean('estado')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Índices
            $table->index('usuario_id');
            $table->index('numero_certificado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificados');
    }
}; 