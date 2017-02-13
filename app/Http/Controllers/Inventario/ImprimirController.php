<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventario\ModulosAjustes;
use App\Http\Requests\ImprimirInventarioRequest;
use App\Inventario\Inventario;
use App\Inventario\Catalogo;

class ImprimirController extends Controller
{
    use ModulosAjustes;

    public function showForm()
    {
        $moduloSelected = 'imprimir';
        $inventario     = Inventario::getInventarioActivo();
        $maxHoja        = $inventario->getMaxHoja();

        return view('inventario.imprime_inventario', compact('moduloSelected', 'inventario', 'maxHoja'));
    }

    public function imprimir(ImprimirInventarioRequest $request)
    {
        $inventario     = Inventario::getInventarioActivo();
        $ocultaStockSAP = request()->input('oculta_stock_sap');
        $catalogo       = Catalogo::class;

        $hojasInventario = [];
        for ($hoja = request()->input('pag_desde'); $hoja <= request()->input('pag_hasta'); $hoja++)
        {
            $hojasInventario[$hoja] = $inventario->getDetalleHoja($hoja);
        }

        return view('inventario.print', compact('inventario', 'hojasInventario', 'ocultaStockSAP', 'catalogo'));
    }
}
