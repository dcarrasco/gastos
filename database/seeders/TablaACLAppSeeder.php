<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'created_at' => now(),
        ]);

        // 2
        DB::table('acl_app')->insert([
            'app' => 'ACL',
            'descripcion' => 'Access Control List',
            'orden' => 90,
            'url' => '',
            'icono' => 'users',
            'created_at' => now(),
        ]);
    }
}
