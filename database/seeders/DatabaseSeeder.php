<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        // ---------------------------------------------------------------------
        // ACL
        // ---------------------------------------------------------------------
        $this->call(TablaACLUsuariosSeeder::class);
        $this->call(TablaACLAppSeeder::class);
        $this->call(TablaACLModuloSeeder::class);
        $this->call(TablaACLRolSeeder::class);
        $this->call(TablaACLRolModuloSeeder::class);
        $this->call(TablaACLUsuarioRolSeeder::class);
    }
}
