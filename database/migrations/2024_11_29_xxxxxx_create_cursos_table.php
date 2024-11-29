
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursosTable extends Migration
{
    public function up()
    {
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2);
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->unsignedBigInteger('tipo_curso_id');
            $table->timestamps();

            $table->foreign('tipo_curso_id')->references('id')->on('tipos_cursos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cursos');
    }
}