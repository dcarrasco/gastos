<?php

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
        $this->call(TablaFijaUsuariosSeeder::class);
        $this->call(TablaACLAppSeeder::class);
        $this->call(TablaACLModuloSeeder::class);
        $this->call(TablaACLRolSeeder::class);
        $this->call(TablaACLRolModuloSeeder::class);
        $this->call(TablaACLUsuarioRolSeeder::class);

        // ---------------------------------------------------------------------
        // Inventario
        // ---------------------------------------------------------------------
        // $this->call(TablaFijaAuditoresSeeder::class);
        // $this->call(TablaFijaFamiliasSeeder::class);
        // $this->call(TablaFijaCatalogosSeeder::class);
        // $this->call(TablaFijaTiposInventarioSeeder::class);
        // $this->call(TablaFijaInventariosSeeder::class);
        // $this->call(TablaFijaTipoUbicacionSeeder::class);
        // $this->call(TablaFijaCentrosSeeder::class);
        // $this->call(TablaFijaAlmacenesSeeder::class);
        // $this->call(TablaFijaUnidadesSeeder::class);
        // $this->call(TablaFijaDetalleInventarioSeeder::class);

        // ---------------------------------------------------------------------
        // Stock
        // ---------------------------------------------------------------------
        // $this->call(TablaCpTiposalmSeeder::class);
        // $this->call(TablaCpAlmacenesSeeder::class);
        // $this->call(TablaCpTipoClasifalmSeeder::class);
        // $this->call(TablaCpClasifalmSeeder::class);
        // $this->call(TablaCpProveedoresSeeder::class);
        // $this->call(TablaCpUsuariosSeeder::class);
        // $this->call(TablaCpCmvSeeder::class);

        // ---------------------------------------------------------------------
        // TOA
        // ---------------------------------------------------------------------
        // $this->call(TablaToaTecnicosSeeder::class);
        // $this->call(TablaToaEmpresasSeeder::class);
        // $this->call(TablaToaTipMaterialTrabajoSeeder::class);
        // $this->call(TablaToaTiposTrabajoSeeder::class);
        // $this->call(TablaToaCiudadesSeeder::class);
        // $this->call(TablaToaEmpresasCiudadesSeeder::class);
    }
}
