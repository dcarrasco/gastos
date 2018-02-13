<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaCpTiposAlmacenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_tipos_almacenes', function (Blueprint $table) {
            $table->integer('id_tipo')->unsingned();
            $table->string('centro', 10);
            $table->string('cod_almacen', 10);
            $table->primary(['id_tipo', 'centro', 'cod_almacen']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_tipos_almacenes');
    }
}
