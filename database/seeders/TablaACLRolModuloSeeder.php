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
        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 1,
            'abilities' => '["view", "view-any", "create", "update", "delete"]',
            'created_at' => now(),
        ]);

        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 2,
            'abilities' => '["view-any", "create", "delete"]',
            'created_at' => now(),
        ]);

        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 3,
            'abilities' => '["view-any", "create", "delete"]',
            'created_at' => now(),
        ]);

        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 4,
            'abilities' => '["create", "delete"]',
            'created_at' => now(),
        ]);

        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 5,
            'abilities' => '["view-any", "create", "delete"]',
            'created_at' => now(),
        ]);

        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 6,
            'abilities' => '["view", "view-any", "create", "update", "delete"]',
            'created_at' => now(),
        ]);

        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 7,
            'abilities' => '["view-any", "create", "delete"]',
            'created_at' => now(),
        ]);
    }
}
