<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaCpTipoClasifalm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_tipo_clasifalm', function (Blueprint $table) {
            $table->increments('id_tipoclasif');
            $table->string('tipo', 50);
            $table->string('color', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_tipo_clasifalm');
    }
}
