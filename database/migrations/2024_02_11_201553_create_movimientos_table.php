<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cash_movimientos', function (Blueprint $table) {
            $table->id();
            $table->uuid("movimiento_id")->index("idx_movimiento_id");

            $table->foreignId("cuenta_id")->references("id")->on("cash_cuentas");
            $table->date("fecha");
            $table->string("numero")->nullable();
            $table->string("descripcion")->nullable();
            $table->foreignId("contracuenta_id")->references("id")->on("cash_cuentas");

            $table->string("conciliado");
            $table->string("tipo_cargo");
            $table->integer("monto");
            $table->integer("balance");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_movimientos');
    }
};
