<?php

use Illuminate\Database\Seeder;

class TablaFijaCatalogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\Catalogo::class, 200)->create();
    }
}
