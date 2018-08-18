<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaFijaCatalogos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fija_catalogos', function (Blueprint $table) {
            $table->string('catalogo', 20);
            $table->string('descripcion', 50);
            $table->float('pmp', 12, 2);
            $table->boolean('es_seriado')->default(0);
            $table->primary(['catalogo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fija_catalogos');
    }
}
