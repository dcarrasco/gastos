<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->foreignId('app_id');
            $table->string('modulo', 50)->unique();
            $table->string('descripcion', 100);
            $table->string('llave_modulo', 100);
            $table->string('icono', 50);
            $table->string('url', 100);
            $table->integer('orden');
            $table->timestamps();

            $table->foreign('app_id')->references('id')->on('acl_app');
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
