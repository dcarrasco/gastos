<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTiposCuenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cta_tipos_cuentas', function(Blueprint $table) {
           $table->integer('tipo')->unsigned()->after('tipo_cuenta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cta_tipos_cuentas', function(Blueprint $table) {
           $table->dropColumn('tipo');
        });
    }
}
