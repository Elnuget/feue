<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTiposCursosTable extends Migration
{
    public function up()
    {
        Schema::create('tipos_cursos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tipos_cursos');
    }
}