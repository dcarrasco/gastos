<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inventario;
use App\Catalogo;

class ReportesController extends Controller
{
    private function menuModuloReporte()
    {
        return [
            'hoja' => [
                'nombre' => trans('inventario.menu_reporte_hoja'),
                'icono'  => 'file-text-o'
            ],
            'material' => [
                'nombre' => trans('inventario.menu_reporte_mat'),
                'icono'  => 'barcode'
            ],
            'materialFaltante' => [
                'nombre' => trans('inventario.menu_reporte_faltante'),
                'icono'  => 'tasks'
            ],
            'ubicacion' => [
                'nombre' => trans('inventario.menu_reporte_ubicacion'),
                'icono'  => 'map-marker'
            ],
            'tiposUbicacion' => [
                'nombre' => trans('inventario.menu_reporte_tip_ubic'),
                'icono'  => 'th'
            ],
            'ajustes' => [
                'nombre' => trans('inventario.menu_reporte_ajustes'),
                'icono'  => 'wrench'
            ],
        ];
    }

    public function reporte(Request $request, $tipo = null)
    {
        // dump($request->input());

        $menuModulo = $this->menuModuloReporte();
        $moduloSelected = empty($tipo) ? collect(array_keys($menuModulo))->first() : $tipo;
        $moduloRouteName = 'inventario.reporte';

        $inventarioID = request()->input('inventario', Inventario::getInventarioActivo()->id);
        $comboInventario = Inventario::getInventarioActivo()->getModelFormOptions();
        $inventario = Inventario::find($inventarioID);
        $reporte = $inventario->reporte($moduloSelected, $tipo);

        return view('inventario.reporte', compact('inventarioID', 'comboInventario', 'menuModulo', 'moduloSelected', 'moduloRouteName', 'reporte'));
    }
}
