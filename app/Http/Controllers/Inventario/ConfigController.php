<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends Controller
{
    use OrmController;

    public $routeName = 'inventarioConfig';

    public $menuModulo = [];

    public function __construct()
    {
        $this->menuModulo = [
            // 'detalleInventario' => ['nombre' => 'Detalle inventario', 'icono' => 'list'],
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

        view()->share('menuModulo', $this->menuModulo);
        view()->share('moduloSelected', \Request::segment(2));
        view()->share('moduloRouteName', $this->routeName.'.index');
        view()->share('routeName', $this->routeName);
    }


}
