<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Inventario\Catalogo;
use App\Inventario\Inventario;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventario\ModulosReportes;

class ReportesController extends Controller
{
    use ModulosReportes;

    public function reporte(Request $request, $tipo = null)
    {
        $moduloSelected = empty($tipo) ? collect(array_keys($this->menuModulo))->first() : $tipo;

        $idInventario = request('inventario', Inventario::getIdInventarioActivo());
        $comboInventario = Inventario::getModelFormOptions();

        $claseReporte = '\App\Inventario\Reporte\Reporte'.ucfirst($moduloSelected);
        $reporte = (new $claseReporte($idInventario))->reporte($moduloSelected);

        return view('inventario.reporte', compact('idInventario', 'comboInventario', 'moduloSelected', 'reporte'));
    }
}
