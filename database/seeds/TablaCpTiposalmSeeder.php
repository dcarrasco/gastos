<?php

use Illuminate\Database\Seeder;

class TablaCpTiposalmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Stock\TipoAlmacenSap::class, 5)->create();
    }
}
