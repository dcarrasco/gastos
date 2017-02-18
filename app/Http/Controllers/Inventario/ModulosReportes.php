<?php

namespace App\Http\Controllers\Inventario;

trait ModulosReportes
{
    protected $routeName = 'inventario.reporte';

    protected $menuModulo = [];

    public function __construct()
    {
        $this->menuModulo = [
            'hoja' => [
                'nombre' => trans('inventario.menu_reporte_hoja'),
                'url'    => route($this->routeName, ['hoja']),
                'icono'  => 'file-text-o'
            ],
            'material' => [
                'nombre' => trans('inventario.menu_reporte_mat'),
                'url'    => route($this->routeName, ['material']),
                'icono'  => 'barcode'
            ],
            'materialFaltante' => [
                'nombre' => trans('inventario.menu_reporte_faltante'),
                'url'    => route($this->routeName, ['materialFaltante']),
                'icono'  => 'tasks'
            ],
            'ubicacion' => [
                'nombre' => trans('inventario.menu_reporte_ubicacion'),
                'url'    => route($this->routeName, ['ubicacion']),
                'icono'  => 'map-marker'
            ],
            'tiposUbicacion' => [
                'nombre' => trans('inventario.menu_reporte_tip_ubic'),
                'url'    => route($this->routeName, ['tiposUbicacion']),
                'icono'  => 'th'
            ],
            'ajustes' => [
                'nombre' => trans('inventario.menu_reporte_ajustes'),
                'url'    => route($this->routeName, ['ajustes']),
                'icono'  => 'wrench'
            ],
        ];

        view()->share('menuModulo', $this->menuModulo);
    }
}
