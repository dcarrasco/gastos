<?php

use Illuminate\Database\Seeder;

class TablaCpClasifalmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Stock\ClasifAlmacenSap::class, 15)->create();
    }
}
