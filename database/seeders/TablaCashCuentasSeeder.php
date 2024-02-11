<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaCashCuentasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('cash_cuentas')->insert([
            'nombre' => 'root',
            'codigo' => '0',
            'descripcion' => 'root',
            'tipo_cuenta' => 'root',
            'moneda' => 'root',
            'color' => '',
            'limite_superior' => null,
            'limite_inferior' => null,
            'contenedor' => true,
            'oculto' => true,
            'cuenta_superior_id' => 1,
            'created_at' => now()
        ]);
    }
}
