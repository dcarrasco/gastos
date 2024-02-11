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
        Schema::create('cash_cuentas', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("codigo")->nullable();
            $table->string("descripcion")->nullable();
            $table->string("tipo_cuenta");
            $table->string("moneda");
            $table->string("color")->nullable();
            $table->integer("limite_superior")->nullable();
            $table->integer("limite_inferior")->nullable();
            $table->boolean("contenedor");
            $table->boolean("oculto");
            $table->foreignId("cuenta_superior_id")->references("id")->on("cash_cuentas");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_cuentas');
    }
};
