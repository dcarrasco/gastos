<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreaTablaToaCatalogoTipMaterial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('toa_catalogo_tip_material', function (Blueprint $table) {
            $table->string('id_catalogo', 20);
            $table->integer('id_tip_material_trabajo')->unsigned();
            $table->primary(['id_catalogo', 'id_tip_material_trabajo'], 'toa_catalogo_tip_material_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('toa_catalogo_tip_material');
    }
}
