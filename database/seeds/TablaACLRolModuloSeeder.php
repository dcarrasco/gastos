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
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 1]);

        // ---------------------------------------------------------------------
        // Inventario
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['rol_id' => 2, 'modulo_id' => 2]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 2, 'modulo_id' => 3]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 2, 'modulo_id' => 4]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 2, 'modulo_id' => 5]);

        // ---------------------------------------------------------------------
        // Stock
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['rol_id' => 3, 'modulo_id' => 6]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 3, 'modulo_id' => 7]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 3, 'modulo_id' => 8]);

        // ---------------------------------------------------------------------
        // TOA
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['rol_id' => 4, 'modulo_id' => 9]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 4, 'modulo_id' => 10]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 4, 'modulo_id' => 11]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 4, 'modulo_id' => 12]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 4, 'modulo_id' => 13]);

        // ---------------------------------------------------------------------
        // GASTOS
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['rol_id' => 5, 'modulo_id' => 14]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 5, 'modulo_id' => 15]);
    }
}
