<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 45);
            $table->string('tipo', 10);
            $table->tinyInteger('activo');
            $table->string('usr', 30)->unique();
            $table->string('pwd', 255);
            $table->string('correo', 40)->unique();
            $table->dateTime('fecha_login');
            $table->string('ip_login', 30);
            $table->string('agente_login', 50);
            $table->integer('login_errors');
            $table->rememberToken();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
