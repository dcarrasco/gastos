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
        // GASTOS
        // ---------------------------------------------------------------------
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 1]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 2]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 3]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 4]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 5]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 6]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 7]);
    }
}
