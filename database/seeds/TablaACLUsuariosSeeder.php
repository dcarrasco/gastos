<?php

use Illuminate\Database\Seeder;

class TablaACLUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('acl_usuarios')->insert([
            'nombre' => 'Laravel Test',
            'activo' => 1,
            'username' => 'laravel',
            'password' => bcrypt('laravel'),
            'email' => 'danielcarrasco17@gmail.com',
            'fecha_login' => now(),
            'ip_login' => '',
            'agente_login' => '',
            'login_errors' => 0,
            'created_at' => now(),
        ]);
    }
}
