<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaFijaTiposInventario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fija_tipos_inventario', function (Blueprint $table) {
            $table->string('id_tipo_inventario', 10);
            $table->string('desc_tipo_inventario', 50);
            $table->timestamps();
            $table->primary(['id_tipo_inventario']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fija_tipos_inventario');
    }
}
