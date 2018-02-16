<?php

use Illuminate\Database\Seeder;

class TablaToaEmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Toa\Empresa::class, 5)->create();
    }
}
