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
            $table->primary(['usuario_id', 'rol_id']);
            $table->foreignId('usuario_id');
            $table->foreignId('rol_id');
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('acl_usuarios');
            $table->foreign('rol_id')->references('id')->on('acl_rol');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('acl_usuario_rol');
    }
}
