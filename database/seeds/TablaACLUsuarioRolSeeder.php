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
        DB::table('acl_usuario_rol')->insert(['id_usuario' => 1, 'id_rol' => 1]);
        DB::table('acl_usuario_rol')->insert(['id_usuario' => 1, 'id_rol' => 2]);
        DB::table('acl_usuario_rol')->insert(['id_usuario' => 1, 'id_rol' => 3]);
        DB::table('acl_usuario_rol')->insert(['id_usuario' => 1, 'id_rol' => 4]);
    }
}
