<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaRolesModulos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_rol_modulo', function (Blueprint $table) {
            $table->primary(['rol_id', 'modulo_id']);
            $table->foreignId('rol_id');
            $table->foreignId('modulo_id');
            $table->timestamps();

            $table->foreign('rol_id')->references('id')->on('acl_rol');
            $table->foreign('modulo_id')->references('id')->on('acl_modulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acl_rol_modulo');
    }
}
