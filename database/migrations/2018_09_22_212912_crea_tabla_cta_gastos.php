<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->foreignId('cuenta_id');
            $table->integer('anno')->nullable()->default(0);
            $table->integer('mes')->nullable()->default(0);
            $table->date('fecha')->nullable();
            $table->string('glosa', 200)->nullable();
            $table->string('serie', 50)->nullable();
            $table->foreignId('tipo_gasto_id');
            $table->foreignId('tipo_movimiento_id');
            $table->biginteger('monto');
            $table->foreignId('usuario_id');
            $table->timestamps();

            $table->foreign('cuenta_id')->references('id')->on('cta_cuentas');
            // $table->foreign('tipo_gasto_id')->references('id')->on('cta_tipos_gastos');
            $table->foreign('tipo_movimiento_id')->references('id')->on('cta_tipos_movimientos');
            $table->foreign('usuario_id')->references('id')->on('acl_usuarios');
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
