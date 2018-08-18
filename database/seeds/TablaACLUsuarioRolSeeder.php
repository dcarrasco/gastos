<?php

use Illuminate\Database\Seeder;

class TablaACLUsuarioRolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acl_usuario_rol')->insert(['usuario_id' => 1, 'rol_id' => 1]);
        DB::table('acl_usuario_rol')->insert(['usuario_id' => 1, 'rol_id' => 2]);
        DB::table('acl_usuario_rol')->insert(['usuario_id' => 1, 'rol_id' => 3]);
        DB::table('acl_usuario_rol')->insert(['usuario_id' => 1, 'rol_id' => 4]);
    }
}
