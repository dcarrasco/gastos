<?php

use Illuminate\Database\Seeder;

class TablaCpAlmacenesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Stock\AlmacenSap::class, 50)->create();
    }
}
