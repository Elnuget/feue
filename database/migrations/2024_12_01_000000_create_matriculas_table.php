<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatriculasTable extends Migration
{
    public function up()
    {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('curso_id');
            $table->date('fecha_matricula');
            $table->decimal('monto_total', 10, 2);
            $table->decimal('valor_pendiente', 10, 2)->nullable();
            $table->enum('estado_matricula', ['Pendiente', 'Aprobada', 'Completada', 'Rechazada'])->default('Pendiente');
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('curso_id')->references('id')->on('cursos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('matriculas');
    }
}