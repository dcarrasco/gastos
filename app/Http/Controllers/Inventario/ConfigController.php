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
            'auditor' => [
                'nombre' => trans('inventario.config_menu_auditores'),
                'url'    => route($this->routeName.'.index', ['auditor']),
                'icono'  => 'user',
            ],
            'familia' => [
                'nombre' => trans('inventario.config_menu_familias'),
                'url'    => route($this->routeName.'.index', ['familia']),
                'icono'  => 'th'
            ],
            'catalogo' => [
                'nombre' => trans('inventario.config_menu_materiales'),
                'url'    => route($this->routeName.'.index', ['catalogo']),
                'icono'  => 'barcode'
            ],
            'tipoInventario' => [
                'nombre' => trans('inventario.config_menu_tipos_inventarios'),
                'url'    => route($this->routeName.'.index', ['tipoInventario']),
                'icono'  => 'th'
            ],
            'inventario' => [
                'nombre' => trans('inventario.config_menu_inventarios'),
                'url'    => route($this->routeName.'.index', ['inventario']),
                'icono'  => 'list'
            ],
            'tipoUbicacion' => [
                'nombre' => trans('inventario.config_menu_tipo_ubicacion'),
                'url'    => route($this->routeName.'.index', ['tipoUbicacion']),
                'icono'  => 'th'
            ],
            'centro' => [
                'nombre' => trans('inventario.config_menu_centros'),
                'url'    => route($this->routeName.'.index', ['centro']),
                'icono'  => 'th'
            ],
            'almacen' => [
                'nombre' => trans('inventario.config_menu_almacenes'),
                'url'    => route($this->routeName.'.index', ['almacen']),
                'icono'  => 'home'
            ],
            'unidadMedida' => [
                'nombre' => trans('inventario.config_menu_unidades_medida'),
                'url'    => route($this->routeName.'.index', ['unidadMedida']),
                'icono'  => 'balance-scale'
            ],
        ];

        view()->share('menuModulo', $this->menuModulo);
        view()->share('moduloSelected', empty(request()->segment(2)) ? collect(array_keys($this->menuModulo))->first() : request()->segment(2));
        view()->share('routeName', $this->routeName);
    }


}
