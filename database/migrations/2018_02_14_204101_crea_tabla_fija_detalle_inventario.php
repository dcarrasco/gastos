<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaFijaDetalleInventario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fija_detalle_inventario', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_inventario')->unsigned();
            $table->integer('hoja')->unsigned();
            $table->string('ubicacion', 10);
            $table->string('hu', 20);
            $table->string('catalogo', 20);
            $table->string('descripcion', 45);
            $table->string('lote', 10);
            $table->string('centro', 10);
            $table->string('almacen', 10);
            $table->string('um', 10);
            $table->integer('stock_sap');
            $table->integer('stock_fisico');
            $table->integer('digitador')->unsigned();
            $table->integer('auditor')->unsigned();
            $table->string('reg_nuevo', 1);
            $table->datetime('fecha_modificacion')->nullable();
            $table->string('observacion', 200)->nullable();
            $table->integer('stock_ajuste')->nullable();
            $table->string('glosa_ajuste', 100)->nullable();
            $table->datetime('fecha_ajuste')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fija_detalle_inventario');
    }
}
