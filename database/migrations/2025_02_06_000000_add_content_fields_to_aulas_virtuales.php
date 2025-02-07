<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('aula_virtual_contenidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_virtual_id')->constrained('aulas_virtuales')->onDelete('cascade');
            $table->string('titulo');
            $table->text('contenido')->nullable();
            $table->string('enlace')->nullable();
            $table->string('archivo')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aula_virtual_contenidos');
    }
}; 