<?php

use Illuminate\Database\Seeder;

class TablaToaEmpresasCiudadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Toa\EmpresaCiudad::class, 20)->create();
    }
}
