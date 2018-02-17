<?php

use Illuminate\Database\Seeder;

class TablaCpUsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Stock\UsuarioSap::class, 100)->create();
    }
}
