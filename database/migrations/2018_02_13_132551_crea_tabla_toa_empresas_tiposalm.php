<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaToaEmpresasTiposalm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toa_empresas_tiposalm', function (Blueprint $table) {
            $table->string('id_empresa', 20);
            $table->integer('id_tipo')->unsigned();
            $table->primary(['id_empresa', 'id_tipo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toa_empresas_tiposalm');
    }
}
