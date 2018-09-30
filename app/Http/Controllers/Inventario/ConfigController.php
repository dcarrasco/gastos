<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\OrmModel\Inventario\Centro;
use App\Http\Controllers\Controller;
use App\OrmModel\Inventario\Almacen;
use App\OrmModel\Inventario\Auditor;
use App\OrmModel\Inventario\Familia;
use App\OrmModel\Inventario\Catalogo;
use App\OrmModel\Inventario\Inventario;
use App\OrmModel\Inventario\UnidadMedida;
use App\OrmModel\Inventario\TipoUbicacion;
use App\Http\Controllers\Orm\OrmController;
use App\OrmModel\Inventario\TipoInventario;
use App\OrmModel\Inventario\DetalleInventario;

class ConfigController extends Controller
{
    use OrmController;

    /**
     * Crea una nueva instancia del controlador config
     */
    public function __construct()
    {
        $this->routeName  = 'inventarioConfig';
        $this->menuModulo = [
            Auditor::class,
            Familia::class,
            Catalogo::class,
            TipoInventario::class,
            Inventario::class,
            DetalleInventario::class,
            TipoUbicacion::class,
            Centro::class,
            Almacen::class,
            UnidadMedida::class,
        ];

        $this->makeView();
    }
}
