<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaCashTipoCuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('cash_tipo_cuentas')->insert(['tipo_cuenta' => 'root', 'nombre' => 'Root', 'tipo' => 'root']);

        DB::table('cash_tipo_cuentas')->insert(['tipo_cuenta' => 'banco', 'nombre' => 'Banco', 'tipo' => 'balance']);
        DB::table('cash_tipo_cuentas')->insert(['tipo_cuenta' => 'activo', 'nombre' => 'Activo', 'tipo' => 'balance']);
        DB::table('cash_tipo_cuentas')->insert(['tipo_cuenta' => 'pasivo', 'nombre' => 'Pasivo', 'tipo' => 'balance']);
        DB::table('cash_tipo_cuentas')->insert(['tipo_cuenta' => 'patrimonio', 'nombre' => 'Patrimonio', 'tipo' => 'balance']);

        DB::table('cash_tipo_cuentas')->insert(['tipo_cuenta' => 'gasto', 'nombre' => 'Gasto', 'tipo' => 'resultado']);
        DB::table('cash_tipo_cuentas')->insert(['tipo_cuenta' => 'ingreso', 'nombre' => 'Ingreso', 'tipo' => 'resultado']);
    }
}
