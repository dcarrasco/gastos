<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaCtaGlosaTipoGasto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cta_glosa_tipo_gasto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_id');
            $table->string('glosa', 200)->nullable();
            $table->foreignId('tipo_gasto_id');
            $table->timestamps();

            $table->foreign('cuenta_id')->references('id')->on('cta_cuentas');
            $table->foreign('tipo_gasto_id')->references('id')->on('cta_tipos_gastos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cta_glosa_tipo_gasto');
    }
}
