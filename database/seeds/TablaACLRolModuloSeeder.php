<?php

use Illuminate\Database\Seeder;

class TablaACLRolModuloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ---------------------------------------------------------------------
        // ACL
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['id_rol' => 1, 'id_modulo' => 1]);

        // ---------------------------------------------------------------------
        // Inventario
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['id_rol' => 2, 'id_modulo' => 2]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 2, 'id_modulo' => 3]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 2, 'id_modulo' => 4]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 2, 'id_modulo' => 5]);

        // ---------------------------------------------------------------------
        // Stock
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['id_rol' => 3, 'id_modulo' => 6]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 3, 'id_modulo' => 7]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 3, 'id_modulo' => 8]);

        // ---------------------------------------------------------------------
        // TOA
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['id_rol' => 4, 'id_modulo' => 9]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 4, 'id_modulo' => 10]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 4, 'id_modulo' => 11]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 4, 'id_modulo' => 12]);
        DB::table('acl_rol_modulo')->insert(['id_rol' => 4, 'id_modulo' => 13]);
    }
}
