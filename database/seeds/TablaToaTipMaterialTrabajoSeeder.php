<?php

use Illuminate\Database\Seeder;

class TablaToaTipMaterialTrabajoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Toa\TipMaterialTrabajo::class, 5)->create();
    }
}
