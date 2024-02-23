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
        Schema::table('cash_tipo_cuentas', function (Blueprint $table) {
            $table->text('nombre_cargo')->nullable()->after('tipo');
            $table->integer('signo_cargo')->nullable()->after('nombre_cargo');
            $table->text('nombre_abono')->nullable()->after('signo_cargo');
            $table->integer('signo_abono')->nullable()->after('nombre_abono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acl_rol_modulo', function (Blueprint $table) {
            $table->dropColumn('nombre_cargo');
            $table->dropColumn('signo_cargo');
            $table->dropColumn('nombre_abono');
            $table->dropColumn('signo_abono');
        });
    }
};
