<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaFijaTipoUbicacion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fija_tipo_ubicacion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_inventario', 10);
            $table->string('tipo_ubicacion', 30);
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
        Schema::dropIfExists('fija_tipo_ubicacion');
    }
}
