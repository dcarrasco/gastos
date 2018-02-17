<?php

use Illuminate\Database\Seeder;

class TablaCpTipoClasifalmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Stock\TipoClasifAlmacenSap::class, 5)->create();
    }
}
