<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaCtaTiposGastos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cta_tipos_gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_movimiento_id');
            $table->string('tipo_gasto', 50)->unique();
            $table->timestamps();

            $table->foreign('tipo_movimiento_id')->references('id')->on('cta_tipos_movimientos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cta_tipos_gastos');
    }
}
