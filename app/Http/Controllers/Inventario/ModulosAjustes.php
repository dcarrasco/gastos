<?php

namespace App\Http\Controllers\Inventario;

trait ModulosAjustes
{
    protected $routeName = 'inventario.reporte';

    protected $menuModulo = [];

    public function __construct()
    {
        $this->menuModulo = [
            'ajustes' => [
                'nombre' => trans('inventario.menu_ajustes'),
                'url'    => route('inventario.ajustes'),
                'icono'  => 'wrench'
            ],
            'subir-stock' => [
                'nombre' => trans('inventario.menu_upload'),
                'url'    => route('inventario.upload'),
                'icono'  => 'cloud-upload'
            ],
            'imprimir' => [
                'nombre' => trans('inventario.menu_print'),
                'url'    => route('inventario.imprimir'),
                'icono'  => 'print'
            ],
            'actualiza_precios' => [
                'nombre' => trans('inventario.menu_act_precios'),
                'url'    => '',
                'icono'  => 'usd'
            ],
        ];

        view()->share('menuModulo', $this->menuModulo);
    }
}
