<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaUsuariosRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_usuario_rol', function (Blueprint $table) {
            $table->integer('id_usuario');
            $table->integer('id_rol');
            $table->timestamps();
            $table->primary(['id_usuario', 'id_rol']);
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
