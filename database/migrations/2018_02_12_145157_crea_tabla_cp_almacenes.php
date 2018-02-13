<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaCpAlmacenes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_almacenes', function (Blueprint $table) {
            $table->string('centro', 10);
            $table->string('cod_almacen', 10);
            $table->string('des_almacen', 50);
            $table->string('uso_almacen', 50);
            $table->string('responsable', 50);
            $table->string('tipo_op', 50);
            $table->primary(['centro', 'cod_almacen']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_almacenes');
    }
}
