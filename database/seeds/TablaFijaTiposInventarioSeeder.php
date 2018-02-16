<?php

use Illuminate\Database\Seeder;

class TablaFijaTiposInventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\TipoInventario::class, 5)->create();
    }
}
