<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('aulas_virtuales', function (Blueprint $table) {
            $table->foreignId('curso_id')
                  ->after('id')
                  ->constrained('cursos')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('aulas_virtuales', function (Blueprint $table) {
            $table->dropForeign(['curso_id']);
            $table->dropColumn('curso_id');
        });
    }
}; 