<?php

namespace App\Http\Controllers\Toa;

trait ModulosControles
{
    protected $routeName = 'toa.controles';

    protected $menuModulo = [];

    public function __construct()
    {
        $this->menuModulo = [
            'tecnicos' => [
                'nombre' => trans('toa.controles_tecnicos'),
                'url'    => route($this->routeName, ['tecnicos']),
                'icono'  => 'user'
            ],
            'materiales' => [
                'nombre' => trans('toa.controles_materiales_consumidos'),
                'url'    => route($this->routeName, ['materiales']),
                'icono'  => 'tv'
            ],
            'materiales-tipo-trabajo' => [
                'nombre' => trans('toa.controles_materiales'),
                'url'    => route('toa.controles'),
                'icono'  => 'file-text-o'
            ],
            'asignaciones' => [
                'nombre' => trans('toa.controles_asignaciones'),
                'url'    => route('toa.controles'),
                'icono'  => 'archive'
            ],
            'stock' => [
                'nombre' => trans('toa.controles_stock'),
                'url'    => route('toa.controles'),
                'icono'  => 'signal'
            ],
            'stock_tecnicos' => [
                'nombre' => trans('toa.controles_stock_tecnicos'),
                'url'    => route('toa.controles'),
                'icono'  => 'truck'
            ],
            'nuevos_tecnicos' => [
                'nombre' => trans('toa.controles_nuevos_tecnicos'),
                'url'    => route('toa.controles'),
                'icono'  => 'users'
            ],
            'clientes' => [
                'nombre' => trans('toa.controles_clientes'),
                'url'    => route('toa.controles'),
                'icono'  => 'users'
            ],
        ];

        view()->share('menuModulo', $this->menuModulo);
    }
}
