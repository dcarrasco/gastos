<?php

use Illuminate\Database\Seeder;

class TablaFijaFamiliasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\Familia::class, 20)->create();
    }
}
