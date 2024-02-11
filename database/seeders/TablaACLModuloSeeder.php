<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaACLModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ---------------------------------------------------------------------
        // Gastos
        // ---------------------------------------------------------------------
        // 1
        DB::table('acl_modulo')->insert([
            'app_id' => 1,
            'modulo' => 'Config Gastos',
            'descripcion' => 'Configuración gastos',
            'llave_modulo' => 'sv8346jhsguan2o',
            'icono' => 'cogs',
            'url' => 'gastosConfig.index',
            'orden' => 90,
            'created_at' => now(),
        ]);

        // 2
        DB::table('acl_modulo')->insert([
            'app_id' => 1,
            'modulo' => 'Ingreso Gastos',
            'descripcion' => 'Ingreso gastos',
            'llave_modulo' => 'insh85tons930ic',
            'icono' => 'money',
            'url' => 'gastos.showMes',
            'orden' => 10,
            'created_at' => now(),
        ]);

        // 3
        DB::table('acl_modulo')->insert([
            'app_id' => 1,
            'modulo' => 'Reporte gastos',
            'descripcion' => 'Reporte gastos',
            'llave_modulo' => 'pd7nd92jtopol0q',
            'icono' => 'table',
            'url' => 'gastos.reporte',
            'orden' => 20,
            'created_at' => now(),
        ]);

        // 4
        DB::table('acl_modulo')->insert([
            'app_id' => 1,
            'modulo' => 'Ingreso masivo',
            'descripcion' => 'Ingreso masivo',
            'llave_modulo' => 'oms73ueg39dmn03',
            'icono' => 'calculator',
            'url' => 'gastos.ingresoMasivo',
            'orden' => 40,
            'created_at' => now(),
        ]);

        // 5
        DB::table('acl_modulo')->insert([
            'app_id' => 1,
            'modulo' => 'Ingreso inversiones',
            'descripcion' => 'Ingreso inversiones',
            'llave_modulo' => '837dhpo93hnbq3v',
            'icono' => 'line-chart',
            'url' => 'gastos.ingresoInversion',
            'orden' => 30,
            'created_at' => now(),
        ]);

        // 6
        DB::table('acl_modulo')->insert([
            'app_id' => 2,
            'modulo' => 'Config ACL',
            'descripcion' => 'Configuracion Access Control List',
            'llave_modulo' => '4bd0769215f77e7',
            'icono' => 'users',
            'url' => 'aclConfig.index',
            'orden' => 100,
            'created_at' => now(),
        ]);

        // 7
        DB::table('acl_modulo')->insert([
            'app_id' => 1,
            'modulo' => 'Gastos totales',
            'descripcion' => 'Reporte Gastos Totales',
            'llave_modulo' => 'jinkns816tGF172',
            'icono' => 'table',
            'url' => 'gastos.reporteTotalGastos',
            'orden' => 21,
            'created_at' => now(),
        ]);

        // 8
        DB::table('acl_modulo')->insert([
            'app_id' => 3,
            'modulo' => 'Config Cash',
            'descripcion' => 'Configuración cash',
            'llave_modulo' => 'jsai2238odsd__qw',
            'icono' => 'cogs',
            'url' => 'cashConfig.index',
            'orden' => 90,
            'created_at' => now(),
        ]);
    }
}
