<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // Primero creamos la nueva columna
            $table->string('carnet', 50)->nullable()->after('numero_referencia');
        });

        // Copiamos los datos de la columna antigua a la nueva
        DB::statement('UPDATE user_profiles SET carnet = numero_referencia');

        Schema::table('user_profiles', function (Blueprint $table) {
            // Eliminamos la columna antigua
            $table->dropColumn('numero_referencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            // Recreamos la columna antigua
            $table->string('numero_referencia', 50)->nullable()->after('carnet');
        });

        // Copiamos los datos de vuelta
        DB::statement('UPDATE user_profiles SET numero_referencia = carnet');

        Schema::table('user_profiles', function (Blueprint $table) {
            // Eliminamos la nueva columna
            $table->dropColumn('carnet');
        });
    }
};
