<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Comprobamos si la tabla ya existe y si ya tiene la columna
        if (Schema::hasTable('entregas') && !Schema::hasColumn('entregas', 'fecha_entrega')) {
            Schema::table('entregas', function (Blueprint $table) {
                $table->timestamp('fecha_entrega')->nullable()->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('entregas') && Schema::hasColumn('entregas', 'fecha_entrega')) {
            Schema::table('entregas', function (Blueprint $table) {
                $table->dropColumn('fecha_entrega');
            });
        }
    }
}; 