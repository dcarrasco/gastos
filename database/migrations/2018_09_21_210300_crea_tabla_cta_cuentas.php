<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaCtaCuentas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cta_cuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banco_id');
            $table->foreignId('tipo_cuenta_id');
            $table->string('cuenta', 50)->nullable();
            $table->timestamps();

            $table->foreign('banco_id')->references('id')->on('cta_bancos');
            $table->foreign('tipo_cuenta_id')->references('id')->on('cta_tipos_cuentas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cta_cuentas');
    }
}
