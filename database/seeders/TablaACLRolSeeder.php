<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TablaACLRolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acl_rol')->insert([
            'app_id' => 1,
            'rol' => 'Rol Gastos',
            'descripcion' => 'Rol Gastos',
            'created_at' => now(),
        ]);

        DB::table('acl_rol')->insert([
            'app_id' => 2,
            'rol' => 'Rol ACL',
            'descripcion' => 'Rol ACL',
            'created_at' => now(),
        ]);
    }
}
