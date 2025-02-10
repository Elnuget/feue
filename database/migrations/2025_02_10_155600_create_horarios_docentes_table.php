<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('horarios_docentes', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // El docente (usuario con rol=Docente)
            $table->unsignedBigInteger('user_id');
            
            // El curso en el que dicta la clase
            $table->unsignedBigInteger('curso_id');
            
            // Día de la semana (1=Lusnes, 2=Martes, etc.)
            $table->tinyInteger('dia_semana'); 
            
            // Hora de inicio y fin
            $table->time('hora_inicio');
            $table->time('hora_fin');
            
            // Aula (opcional, si aplica)
            $table->string('aula', 100)->nullable();
            
            // timestamps (created_at, updated_at)
            $table->timestamps();
            
            // Claves foráneas
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
            
            $table->foreign('curso_id')
                  ->references('id')
                  ->on('cursos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('horarios_docentes');
    }
};
