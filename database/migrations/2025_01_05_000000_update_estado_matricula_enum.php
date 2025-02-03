<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateEstadoMatriculaEnum extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Primero obtenemos la definición actual del ENUM
        $enumValues = DB::select("SHOW COLUMNS FROM matriculas WHERE Field = 'estado_matricula'")[0]->Type;
        
        // Extraemos los valores actuales del ENUM
        preg_match("/^enum\((.*)\)$/", $enumValues, $matches);
        $currentValues = str_getcsv($matches[1], ",", "'");
        
        // Verificamos si 'Entregado' ya existe en el ENUM
        if (!in_array('Entregado', $currentValues)) {
            // Agregamos 'Entregado' a los valores existentes
            $currentValues[] = 'Entregado';
            
            // Construimos la nueva definición del ENUM
            $newEnum = "'" . implode("','", $currentValues) . "'";
            
            // Actualizamos la columna manteniendo los valores existentes
            DB::statement("ALTER TABLE matriculas MODIFY COLUMN estado_matricula ENUM($newEnum) DEFAULT 'Entregado'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        // En el rollback, solo cambiamos el valor por defecto a 'Pendiente'
        // pero mantenemos 'Entregado' como opción válida
        $enumValues = DB::select("SHOW COLUMNS FROM matriculas WHERE Field = 'estado_matricula'")[0]->Type;
        preg_match("/^enum\((.*)\)$/", $enumValues, $matches);
        $currentValues = str_getcsv($matches[1], ",", "'");
        
        $newEnum = "'" . implode("','", $currentValues) . "'";
        DB::statement("ALTER TABLE matriculas MODIFY COLUMN estado_matricula ENUM($newEnum) DEFAULT 'Pendiente'");
    }
}