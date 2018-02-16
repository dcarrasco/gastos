<?php

use Illuminate\Database\Seeder;

class TablaFijaCentrosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\Centro::class, 5)->create();
    }
}
