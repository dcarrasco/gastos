<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acl_rol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('app_id');
            $table->string('rol', 50)->unique();
            $table->string('descripcion', 100);
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
        Schema::dropIfExists('acl_rol');
    }
}
