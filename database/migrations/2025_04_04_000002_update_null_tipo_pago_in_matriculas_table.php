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
        DB::table('matriculas')
            ->whereNull('tipo_pago')
            ->update(['tipo_pago' => 'Pago Único']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('matriculas')
            ->where('tipo_pago', 'Pago Único')
            ->update(['tipo_pago' => null]);
    }
}; 