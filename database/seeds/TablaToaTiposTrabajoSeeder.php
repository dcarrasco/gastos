<?php

use Illuminate\Database\Seeder;

class TablaToaTiposTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Toa\TipoTrabajo::class, 5)->create();
    }
}
