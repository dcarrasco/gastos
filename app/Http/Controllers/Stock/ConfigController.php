<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends Controller
{
    use OrmController;

    public function __construct()
    {
        $this->routeName  = 'stockConfig';
        $this->menuModulo = [
            'almacenSap'              => ['nombre'=>trans('stock.config_menu_alm'), 'icono'=>'home'],
            'tipoAlmacenSap'          => ['nombre'=>trans('stock.config_menu_tipalm'), 'icono'=>'th',],
            'clasifAlmacenSap'        => ['nombre'=>trans('stock.config_menu_clasifalm'), 'icono'=>'th',],
            'tipoClasifAlmacenSap'    => ['nombre'=>trans('stock.config_menu_tipo_clasifalm'), 'icono'=>'th',],
            'proveedor'               => ['nombre'=>trans('stock.config_menu_proveedores'), 'icono'=>'shopping-cart',],
            'usuarioSap'              => ['nombre'=>trans('stock.config_menu_usuarios_sap'), 'icono'=>'user',],
            'almacenes_no_ingresados' => ['nombre'=>trans('stock.config_menu_alm_no_ing'), 'icono'=>'home',],
            'claseMovimiento'         => ['nombre'=>trans('stock.config_menu_cmv'), 'icono'=>'th',],
        ];

        $this->makeView();
    }


}
