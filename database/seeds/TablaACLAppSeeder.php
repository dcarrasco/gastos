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
            'app' => 'Gastos',
            'descripcion' => 'Gastos DCR',
            'orden' => 50,
            'url' => '',
            'icono' => 'credit-card',
            'created_at' => Carbon\Carbon::now(),
        ]);

        // 2
        DB::table('acl_app')->insert([
            'app' => 'ACL',
            'descripcion' => 'Access Control List',
            'orden' => 90,
            'url' => '',
            'icono' => 'users',
            'created_at' => Carbon\Carbon::now(),
        ]);
    }
}
