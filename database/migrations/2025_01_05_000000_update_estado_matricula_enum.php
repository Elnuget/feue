
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateEstadoMatriculaEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE matriculas MODIFY estado_matricula ENUM('Pendiente', 'Aprobada', 'Completada', 'Rechazada', 'Entregado') NOT NULL DEFAULT 'Pendiente'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE matriculas MODIFY estado_matricula ENUM('Pendiente', 'Aprobada', 'Completada', 'Rechazada') NOT NULL DEFAULT 'Pendiente'");
    }
}