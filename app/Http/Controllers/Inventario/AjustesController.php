<?php

namespace App\Http\Controllers\Inventario;

use App\Inventario\Inventario;
use App\Http\Controllers\Controller;
use App\Http\Requests\Inventario\AjustesRequest;
use App\Http\Controllers\Inventario\ModulosAjustes;

class AjustesController extends Controller
{
    use ModulosAjustes;

    public function showForm()
    {
        $moduloSelected = 'ajustes';
        $inventario     = Inventario::getInventarioActivo();
        $detalleAjustes = $inventario->detalleAjustes();

        return view('inventario.ajustes', compact('detalleAjustes', 'inventario', 'moduloSelected'));
    }

    public function update(AjustesRequest $request)
    {
        $detalleAjustes = Inventario::getInventarioActivo()->detalleAjustes();
        $cantidad       = $detalleAjustes->count();

        $detalleAjustes->each(function ($linea) {
            $linea->stock_ajuste = request('stock_ajuste_'.$linea->id);
            $linea->glosa_ajuste = request('observacion_'.$linea->id);
            $linea->fecha_ajuste = \Carbon\Carbon::now();
            $linea->save();
        });

        return redirect()
            ->route('inventario.ajustes', ['page' => \Request::input('page')])
            ->with('alert_message', trans('inventario.adjust_msg_save', compact('cantidad')));
    }
}
