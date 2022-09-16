<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreaTablaCtaSaldosMes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cta_saldos_mes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_id');
            $table->integer('anno')->nullable()->default(0);
            $table->integer('mes')->nullable()->default(0);
            $table->biginteger('saldo_inicial')->default(0);
            $table->biginteger('saldo_final')->default(0);
            $table->timestamps();

            $table->foreign('cuenta_id')->references('id')->on('cta_cuentas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cta_saldos_mes');
    }
}
