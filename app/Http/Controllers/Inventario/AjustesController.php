<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inventario;

class AjustesController extends Controller
{
    private function menuModuloReporte()
    {
        return [
            'ajustes' => [
                'nombre' => trans('inventario.menu_ajustes'),
                'icono'  => 'wrench'
            ],
            'sube_stock' => [
                'nombre' => trans('inventario.menu_upload'),
                'icono'  => 'cloud-upload'
            ],
            'imprime_inventario' => [
                'nombre' => trans('inventario.menu_print'),
                'icono'  => 'print'
            ],
            'actualiza_precios' => [
                'nombre' => trans('inventario.menu_act_precios'),
                'icono'  => 'usd'
            ],
        ];
    }

    public function ajustes()
    {
        $menuModulo = $this->menuModuloReporte();
        $moduloSelected = '';
        $moduloRouteName = 'inventario.reporte';

        $linksPaginas = '';

        $inventario = Inventario::getInventarioActivo();
        $detalleAjustes = $inventario->detalleAjustes();


        return view('inventario.ajustes', compact('detalleAjustes', 'inventario', 'menuModulo', 'moduloSelected', 'moduloRouteName'));
    }
}
