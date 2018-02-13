<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaCpClasifTipoalm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_clasif_tipoalm', function (Blueprint $table) {
            $table->integer('id_clasif')->unsigned();
            $table->integer('id_tipo')->unsigned();
            $table->primary(['id_clasif', 'id_tipo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_clasif_tipoalm');
    }
}
