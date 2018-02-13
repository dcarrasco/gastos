<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaToaCiudades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toa_ciudades', function (Blueprint $table) {
            $table->string('id_ciudad', 5);
            $table->string('ciudad', 50);
            $table->integer('orden');
            $table->primary(['id_ciudad']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toa_ciudades');
    }
}
