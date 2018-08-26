<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaToaTecnicos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toa_tecnicos', function (Blueprint $table) {
            $table->string('id_tecnico', 20);
            $table->string('tecnico', 50);
            $table->string('rut', 20);
            $table->string('id_empresa', 20)->nullable();
            $table->string('id_ciudad', 5)->nullable();
            $table->timestamps();
            $table->primary(['id_tecnico']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toa_tecnicos');
    }
}
