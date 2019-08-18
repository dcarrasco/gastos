<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTiposMovimientos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cta_tipos_movimientos', function(Blueprint $table) {
           $table->integer('orden')->nullable()->unsigned()->after('signo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cta_tipos_movimientos', function(Blueprint $table) {
           $table->dropColumn('orden');
        });
    }
}
