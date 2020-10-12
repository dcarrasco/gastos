<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregaAbilitiesRolModulo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acl_rol_modulo', function (Blueprint $table) {
            $table->text('abilities')->nullable()->after('modulo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acl_rol_modulo', function (Blueprint $table) {
            $table->dropColumn('abilities');
        });
    }
}
