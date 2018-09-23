<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaCtaGastos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cta_gastos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cuenta_id')->unsigned();
            $table->integer('anno')->nullable()->default(0);
            $table->integer('mes')->nullable()->default(0);
            $table->datetime('fecha')->nullable();
            $table->string('glosa', 200)->nullable();
            $table->string('serie', 50)->nullable();
            $table->integer('tipo_gasto_id')->unsigned();
            $table->integer('tipo_movimiento_id')->unsigned();
            $table->biginteger('monto');
            $table->integer('usuario_id')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cta_gastos');
    }
}
