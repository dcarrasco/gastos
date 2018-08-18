<?php

use Illuminate\Database\Seeder;

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
            'rol' => 'Rol ACL',
            'descripcion' => 'Rol ACL',
        ]);

        DB::table('acl_rol')->insert([
            'app_id' => 2,
            'rol' => 'Rol Inventario',
            'descripcion' => 'Rol Inventario',
        ]);

        DB::table('acl_rol')->insert([
            'app_id' => 3,
            'rol' => 'Rol Stock',
            'descripcion' => 'Rol Stock',
        ]);

        DB::table('acl_rol')->insert([
            'app_id' => 4,
            'rol' => 'Rol TOA',
            'descripcion' => 'Rol TOA',
        ]);
    }
}
