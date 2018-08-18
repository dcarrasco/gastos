<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaModulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_modulo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id')->nullable();
            $table->string('modulo', 50)->unique();
            $table->string('descripcion', 100);
            $table->string('llave_modulo', 100);
            $table->string('icono', 50);
            $table->string('url', 100);
            $table->integer('orden');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acl_modulo');
    }
}
