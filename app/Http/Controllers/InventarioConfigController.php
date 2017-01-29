<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OrmController;

class InventarioConfigController extends Controller
{
    use OrmController;

    public $routeName = 'inventarioConfig';

    public $modelList = [];

    public function __construct()
    {
        $this->modelList = [
            'auditor'        => ['nombre' => trans('inventario.config_menu_auditores'), 'icono' => 'user'],
            'familia'        => ['nombre' => trans('inventario.config_menu_familias'), 'icono' => 'th'],
            'catalogo'       => ['nombre' => trans('inventario.config_menu_materiales'), 'icono' => 'barcode'],
            'tipoInventario' => ['nombre' => trans('inventario.config_menu_tipos_inventarios'), 'icono' => 'th'],
            'inventario'     => ['nombre' => trans('inventario.config_menu_inventarios'), 'icono' => 'list'],
            'tipoUbicacion'  => ['nombre' => trans('inventario.config_menu_tipo_ubicacion'), 'icono' => 'th'],
            'centro'         => ['nombre' => trans('inventario.config_menu_centros'), 'icono' => 'th'],
            'almacen'        => ['nombre' => trans('inventario.config_menu_almacenes'), 'icono' => 'home'],
            'unidadMedida'   => ['nombre' => trans('inventario.config_menu_unidades_medida'), 'icono' => 'balance-scale'],
        ];

        view()->share('modelList', $this->modelList);
        view()->share('modelSelected', \Request::segment(2));
        view()->share('routeName', $this->routeName);
    }


}
