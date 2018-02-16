<?php

use Illuminate\Database\Seeder;

class TablaFijaDetalleInventarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\DetalleInventario::class, 500)->create();
    }
}
