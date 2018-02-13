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
        Schema::create('fija_usuarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 45);
            $table->tinyInteger('activo');
            $table->string('username', 30)->unique();
            $table->string('password', 255);
            $table->string('email', 40)->unique();
            $table->dateTime('fecha_login');
            $table->string('ip_login', 30);
            $table->string('agente_login', 50);
            $table->integer('login_errors');
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('fija_usuarios')->insert([
            'nombre' => 'Laravel Test',
            'activo' => 1,
            'username' => 'laravel',
            'password' => '',
            'correo' => '',
            'fecha_login' => \Carbon\Carbon::now(),
            'ip_login' => '',
            'agente_login' => '',
            'login_errors' => 0,
        ]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fija_usuarios');
    }
}
