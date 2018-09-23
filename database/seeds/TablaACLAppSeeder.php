<?php

use Illuminate\Database\Seeder;

class TablaACLAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1
        DB::table('acl_app')->insert([
            'app' => 'ACL',
            'descripcion' => 'ACL',
            'orden' => 10,
            'url' => '',
            'icono' => 'table',
            'created_at' => Carbon\Carbon::now(),
        ]);

        // 2
        DB::table('acl_app')->insert([
            'app' => 'Inventario',
            'descripcion' => 'Inventario',
            'orden' => 20,
            'url' => '',
            'icono' => 'table',
            'created_at' => Carbon\Carbon::now(),
        ]);

        // 3
        DB::table('acl_app')->insert([
            'app' => 'Stock',
            'descripcion' => 'Stock',
            'orden' => 30,
            'url' => '',
            'icono' => 'table',
            'created_at' => Carbon\Carbon::now(),
        ]);

        // 4
        DB::table('acl_app')->insert([
            'app' => 'TOA',
            'descripcion' => 'TOA',
            'orden' => 40,
            'url' => '',
            'icono' => 'table',
            'created_at' => Carbon\Carbon::now(),
        ]);

        // 5
        DB::table('acl_app')->insert([
            'app' => 'Gastos',
            'descripcion' => 'Gastos DCR',
            'orden' => 50,
            'url' => '',
            'icono' => 'credit-card',
            'created_at' => Carbon\Carbon::now(),
        ]);
    }
}
