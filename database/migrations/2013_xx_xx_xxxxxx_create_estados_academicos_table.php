<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstadosAcademicosTable extends Migration
{
    public function up()
    {
        Schema::create('estados_academicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('estados_academicos');
    }
}