<?php

use Illuminate\Database\Seeder;

class TablaFijaInventariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\Inventario::class, 10)->create();
    }
}
