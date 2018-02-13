<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaCpTiposalm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_tiposalm', function (Blueprint $table) {
            $table->increments('id_tipo');
            $table->string('tipo', 50);
            $table->string('tipo_op', 50);
            $table->boolean('es_sumable');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_tiposalm');
    }
}
