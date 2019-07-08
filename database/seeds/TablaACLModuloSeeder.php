<?php

use Illuminate\Database\Seeder;

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
            'created_at' => Carbon\Carbon::now(),
        ]);

        // 2
        DB::table('acl_modulo')->insert([
            'app_id' => 1,
            'modulo' => 'Ingreso Gastos',
            'descripcion' => 'Ingreso gastos',
            'llave_modulo' => 'insh85tons930ic',
            'icono' => 'calculator',
            'url' => 'gastos.showMes',
            'orden' => 10,
            'created_at' => Carbon\Carbon::now(),
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
            'created_at' => Carbon\Carbon::now(),
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
            'created_at' => Carbon\Carbon::now(),
        ]);

        // 5
        DB::table('acl_modulo')->insert([
            'app_id' => 1,
            'modulo' => 'Ingreso inversiones',
            'descripcion' => 'Ingreso inversiones',
            'llave_modulo' => '837dhpo93hnbq3v',
            'icono' => 'calculator',
            'url' => 'gastos.ingresoInversion',
            'orden' => 30,
            'created_at' => Carbon\Carbon::now(),
        ]);

    }
}
