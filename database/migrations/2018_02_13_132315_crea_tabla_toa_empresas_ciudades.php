<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaToaEmpresasCiudades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toa_empresas_ciudades', function (Blueprint $table) {
            $table->string('id_empresa', 20);
            $table->string('id_ciudad', 5);
            $table->primary(['id_empresa', 'id_ciudad']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toa_empresas_ciudades');
    }
}
