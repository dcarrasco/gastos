<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // ---------------------------------------------------------------------
        // ACL
        // ---------------------------------------------------------------------
        $this->call(TablaACLUsuariosSeeder::class);
        $this->call(TablaACLAppSeeder::class);
        $this->call(TablaACLModuloSeeder::class);
        $this->call(TablaACLRolSeeder::class);
        $this->call(TablaACLRolModuloSeeder::class);
        $this->call(TablaACLUsuarioRolSeeder::class);

        $this->call(TablaCashCuentasSeeder::class);
    }
}
