<?php

use Illuminate\Database\Seeder;

class TablaFijaUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fija_usuarios')->insert([
            'nombre' => 'Laravel Test',
            'activo' => 1,
            'username' => 'laravel',
            'password' => bcrypt('laravel'),
            'email' => '',
            'fecha_login' => \Carbon\Carbon::now(),
            'ip_login' => '',
            'agente_login' => '',
            'login_errors' => 0,
        ]);
    }
}
