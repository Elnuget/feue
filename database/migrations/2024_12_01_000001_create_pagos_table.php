<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('matricula_id');
            $table->unsignedBigInteger('metodo_pago_id'); // Change to unsignedBigInteger
            $table->string('comprobante_pago', 255)->nullable();
            $table->decimal('monto', 10, 2);
            $table->date('fecha_pago');
            $table->decimal('valor_pendiente', 10, 2)->nullable();
            $table->date('fecha_proximo_pago')->nullable();
            $table->timestamps();

            $table->foreign('matricula_id')->references('id')->on('matriculas')->onDelete('cascade');
            $table->foreign('metodo_pago_id')->references('id')->on('metodos_pago')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos');
    }
}