<?php

namespace App\Http\Controllers\Inventario;

use App\Inventario\Catalogo;
use App\Inventario\Inventario;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImprimirInventarioRequest;
use App\Http\Controllers\Inventario\ModulosAjustes;

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
        $ocultaStockSAP = request('oculta_stock_sap');
        $catalogo       = Catalogo::class;

        $hojasInventario = [];
        for ($hoja = request('pag_desde'); $hoja <= request('pag_hasta'); $hoja++)
        {
            $hojasInventario[$hoja] = $inventario->getDetalleHoja($hoja);
        }

        return view('inventario.print', compact('inventario', 'hojasInventario', 'ocultaStockSAP', 'catalogo'));
    }
}
