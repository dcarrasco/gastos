<?php

use Illuminate\Database\Seeder;

class TablaFijaAuditoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Inventario\Auditor::class, 40)->create();
    }
}
