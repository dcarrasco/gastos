<?php

namespace App\Http\Controllers\Stock;

use Illuminate\Http\Request;
use App\OrmModel\Stock\Proveedor;
use App\OrmModel\Stock\AlmacenSap;
use App\OrmModel\Stock\UsuarioSap;
use App\Http\Controllers\Controller;
use App\OrmModel\Stock\TipoAlmacenSap;
use App\OrmModel\Stock\ClaseMovimiento;
use App\OrmModel\Stock\ClasifAlmacenSap;
use App\Http\Controllers\Orm\OrmController;
use App\OrmModel\Stock\TipoClasifAlmacenSap;

class ConfigController extends Controller
{
    use OrmController;

    public function __construct()
    {
        $this->routeName  = 'stockConfig';
        $this->menuModulo = [
            new ClasifAlmacenSap,
            new TipoAlmacenSap,
            new AlmacenSap,
            new TipoClasifAlmacenSap,
            new Proveedor,
            new UsuarioSap,
            new ClaseMovimiento,
            // 'almacenes_no_ingresados' => ['nombre'=>trans('stock.config_menu_alm_no_ing'), 'icono'=>'home',],
        ];

        $this->makeView();
    }
}
