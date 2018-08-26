<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaToaTiposTrabajo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toa_tipos_trabajo', function (Blueprint $table) {
            $table->string('id_tipo', 30);
            $table->string('desc_tipo', 50);
            $table->timestamps();
            $table->primary(['id_tipo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toa_tipos_trabajo');
    }
}
