<?php

use Illuminate\Database\Seeder;

class TablaFijaTipoUbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\TipoUbicacion::class, 10)->create();
    }
}
