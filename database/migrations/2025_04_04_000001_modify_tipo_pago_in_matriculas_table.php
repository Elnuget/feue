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
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn('tipo_pago');
        });

        Schema::table('matriculas', function (Blueprint $table) {
            $table->enum('tipo_pago', ['Pago Único', 'Mensual'])
                  ->after('monto_total')
                  ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $table->dropColumn('tipo_pago');
        });

        Schema::table('matriculas', function (Blueprint $table) {
            $table->enum('tipo_pago', ['Efectivo', 'Transferencia', 'Tarjeta', 'Cheque'])
                  ->after('monto_total')
                  ->nullable();
        });
    }
}; 