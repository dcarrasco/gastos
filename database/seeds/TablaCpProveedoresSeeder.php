<?php

use Illuminate\Database\Seeder;

class TablaCpProveedoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Stock\Proveedor::class, 10)->create();
    }
}
