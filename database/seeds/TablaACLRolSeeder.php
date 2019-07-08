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
            'rol' => 'Rol Gastos',
            'descripcion' => 'Rol Gastos',
            'created_at' => Carbon\Carbon::now(),
        ]);
    }
}
