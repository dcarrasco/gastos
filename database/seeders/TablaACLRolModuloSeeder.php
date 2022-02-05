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
        // 1 Config Gastos
        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 1,
            'abilities' => '["view", "view-any", "create", "update", "delete"]',
            'created_at' => now(),
        ]);

        // 2 Ingreso Gastos
        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 2,
            'abilities' => '["view-any", "create", "update", "delete"]',
            'created_at' => now(),
        ]);

        // 3 Reporte Gastos
        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 3,
            'abilities' => '["view-any", "create", "delete"]',
            'created_at' => now(),
        ]);

        // 4 Ingreso Masivo
        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 4,
            'abilities' => '["create", "delete"]',
            'created_at' => now(),
        ]);

        // 5 Ingreso Inversiones
        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 5,
            'abilities' => '["view-any", "create", "delete"]',
            'created_at' => now(),
        ]);

        // 7 Gastos totales
        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 1,
            'modulo_id' => 7,
            'abilities' => '["view-any", "create", "delete"]',
            'created_at' => now(),
        ]);

        // 6 Config ACL
        DB::table('acl_rol_modulo')->insert([
            'rol_id' => 2,
            'modulo_id' => 6,
            'abilities' => '["view", "view-any", "create", "update", "delete"]',
            'created_at' => now(),
        ]);
    }
}
