<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1) Renombrar la tabla solo si existe horarios_docentes y no existe sesiones_docentes
        if (Schema::hasTable('horarios_docentes') && !Schema::hasTable('sesiones_docentes')) {
            Schema::rename('horarios_docentes', 'sesiones_docentes');
        }

        // 2) Ajustar columnas en la tabla
        Schema::table('sesiones_docentes', function (Blueprint $table) {
            // Eliminamos la columna "dia_semana" si existe
            if (Schema::hasColumn('sesiones_docentes', 'dia_semana')) {
                $table->dropColumn('dia_semana');
            }

            // Agregamos una columna "fecha" con valor por defecto si no existe
            if (!Schema::hasColumn('sesiones_docentes', 'fecha')) {
                $table->date('fecha')->nullable()->default(now()->format('Y-m-d'))->after('curso_id');
            }

            // Hacemos que hora_inicio y hora_fin sean nullable
            $table->time('hora_inicio')->nullable()->change();
            $table->time('hora_fin')->nullable()->change();

            // Añadimos "materia" si no existe
            if (!Schema::hasColumn('sesiones_docentes', 'materia')) {
                $table->string('materia', 150)->nullable()->after('hora_fin');
            }

            // El tema impartido en esa sesión si no existe
            if (!Schema::hasColumn('sesiones_docentes', 'tema_impartido')) {
                $table->text('tema_impartido')->nullable()->after('materia');
            }

            // Observaciones generales si no existe
            if (!Schema::hasColumn('sesiones_docentes', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('tema_impartido');
            }
        });

        // 3) Después de migrar los datos existentes, hacemos fecha NOT NULL
        Schema::table('sesiones_docentes', function (Blueprint $table) {
            $table->date('fecha')->nullable(false)->change();
        });
    }

    public function down()
    {
        // 1) Revertir cambios en las columnas
        Schema::table('sesiones_docentes', function (Blueprint $table) {
            // Eliminamos las columnas nuevas solo si existen
            if (Schema::hasColumn('sesiones_docentes', 'fecha')) {
                $table->dropColumn('fecha');
            }
            if (Schema::hasColumn('sesiones_docentes', 'materia')) {
                $table->dropColumn('materia');
            }
            if (Schema::hasColumn('sesiones_docentes', 'tema_impartido')) {
                $table->dropColumn('tema_impartido');
            }
            if (Schema::hasColumn('sesiones_docentes', 'observaciones')) {
                $table->dropColumn('observaciones');
            }

            // Restauramos "dia_semana" si no existe
            if (!Schema::hasColumn('sesiones_docentes', 'dia_semana')) {
                $table->tinyInteger('dia_semana')->default(1);
            }

            // Volvemos a dejarlas como NO nullable
            $table->time('hora_inicio')->nullable(false)->change();
            $table->time('hora_fin')->nullable(false)->change();
        });

        // 2) Renombrar la tabla de vuelta a "horarios_docentes" solo si existe sesiones_docentes
        if (Schema::hasTable('sesiones_docentes') && !Schema::hasTable('horarios_docentes')) {
            Schema::rename('sesiones_docentes', 'horarios_docentes');
        }
    }
};
