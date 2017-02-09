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
            'imprime_inventario' => [
                'nombre' => trans('inventario.menu_print'),
                'url'    => '',
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
}
