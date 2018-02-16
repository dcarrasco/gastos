<?php

use Illuminate\Database\Seeder;

class TablaToaTecnicosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Toa\Tecnico::class, 20)->create();
    }
}
