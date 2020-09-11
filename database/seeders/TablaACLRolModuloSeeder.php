<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 1, 'created_at' => now()]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 2, 'created_at' => now()]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 3, 'created_at' => now()]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 4, 'created_at' => now()]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 5, 'created_at' => now()]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 6, 'created_at' => now()]);
        DB::table('acl_rol_modulo')->insert(['rol_id' => 1, 'modulo_id' => 7, 'created_at' => now()]);
    }
}
