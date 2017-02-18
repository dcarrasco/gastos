<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventario\ModulosReportes;
use Illuminate\Http\Request;
use App\Inventario\Inventario;
use App\Inventario\Catalogo;

class ReportesController extends Controller
{

    use ModulosReportes;

    public function reporte(Request $request, $tipo = null)
    {
        $moduloSelected = empty($tipo) ? collect(array_keys($this->menuModulo))->first() : $tipo;
        $moduloRouteName = 'inventario.reporte';

        $inventarioID = request()->input('inventario', Inventario::getInventarioActivo()->id);
        $comboInventario = Inventario::getInventarioActivo()->getModelFormOptions();
        $inventario = Inventario::find($inventarioID);
        $reporte = $inventario->reporte($moduloSelected, $tipo);

        return view('inventario.reporte', compact('inventarioID', 'comboInventario', 'moduloSelected', 'reporte'));
    }
}
