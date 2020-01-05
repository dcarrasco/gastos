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
            'email' => 'laravel@laravel.com',
            'fecha_login' => \Carbon\Carbon::now(),
            'ip_login' => '',
            'agente_login' => '',
            'login_errors' => 0,
            'created_at' => Carbon\Carbon::now(),
        ]);

        // factory(App\Acl\Usuario::class, 15)->create();
    }
}
