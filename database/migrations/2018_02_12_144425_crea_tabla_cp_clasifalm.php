<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaCpClasifalm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cp_clasifalm', function (Blueprint $table) {
            $table->increments('id_clasif');
            $table->string('clasificacion', 50);
            $table->integer('orden');
            $table->string('dir_responsable', 20);
            $table->string('estado_ajuste', 20);
            $table->integer('id_tipoclasif')->unsigned()->nullable();
            $table->string('tipo_op', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cp_clasifalm');
    }
}
