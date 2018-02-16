<?php

use Illuminate\Database\Seeder;

class TablaFijaUnidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\UnidadMedida::class, 5)->create();
    }
}
