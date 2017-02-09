<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inventario;

class AjustesController extends Controller
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
                'url'    => route('inventario.subirStockForm'),
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

    public function ajustes()
    {
        $moduloSelected = 'ajustes';

        $inventario = Inventario::getInventarioActivo();
        $detalleAjustes = $inventario->detalleAjustes();

        return view('inventario.ajustes', compact('detalleAjustes', 'inventario', 'moduloSelected'));
    }

    public function update(Request $request)
    {
        $detalleAjustes = Inventario::getInventarioActivo()->detalleAjustes();
        $cantidad = $detalleAjustes->count();

        $detalleAjustes->each(function ($linea) {
            $linea->stock_ajuste = request()->input('stock_ajuste_'.$linea->id);
            $linea->glosa_ajuste = request()->input('observacion_'.$linea->id);
            $linea->fecha_ajuste = \Carbon\Carbon::now();
            $linea->save();
        });

        return redirect()
            ->route('inventario.ajustes', ['page' => \Request::input('page')])
            ->with('alert_message', trans('inventario.adjust_msg_save', compact('cantidad')));
    }

    public function subirStockForm()
    {
        $moduloSelected = 'subir-stock';
        $showScriptCarga = '';
        $inventario = Inventario::getInventarioActivo();
        return view('inventario.sube_stock', compact('showScriptCarga', 'inventario', 'moduloSelected'));
    }

    public function imprimirForm()
    {
        $moduloSelected = 'imprimir';
        $inventario = Inventario::getInventarioActivo();
        $maxHoja = $inventario->getMaxHoja();

        return view('inventario.imprime_inventario', compact('moduloSelected', 'inventario', 'maxHoja'));
    }

    public function imprimir(Request $request)
    {
        $this->validate($request, [
            'pag_desde' => 'required|integer|min:1',
            'pag_hasta' => 'required|integer|min:'.request()->input('pag_desde'),
        ]);
        $inventario = Inventario::getInventarioActivo();
        $ocultaStockSAP = request()->input('oculta_stock_sap');

        $hojasInventario = [];
        for ($hoja = request()->input('pag_desde'); $hoja <= request()->input('pag_hasta'); $hoja++)
        {
            $hojasInventario[$hoja] = $inventario->getDetalleHoja($hoja);
        }

        return view('inventario.print', compact('inventario', 'hojasInventario', 'ocultaStockSAP'));
    }
}
