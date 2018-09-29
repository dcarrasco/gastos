<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaGlosaTipoGasto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cta_glosa_tipo_gasto', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cuenta_id')->unsigned();
            $table->string('glosa', 200)->nullable();
            $table->integer('tipo_gasto_id')->unsigned();
            $table->timestamps();
        });    }

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
