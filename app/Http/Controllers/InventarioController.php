<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventario;
use App\Auditor;

class InventarioController extends Controller
{
    public function index()
    {
        $inventario        = Inventario::getInventarioActivo();
        $hoja              = empty(\Request::input('hoja', 1)) ? 1 : \Request::input('hoja', 1);
        $detalleInventario = $inventario->getDetalleHoja($hoja);
        $linkHojaAnt       = route('inventario.index', ['hoja' => ($hoja > 1) ? $hoja - 1 : 1]);
        $linkHojaSig       = route('inventario.index', ['hoja' => $hoja + 1]);

        return view('inventario.inventario', compact('inventario', 'detalleInventario', 'hoja', 'linkHojaAnt', 'linkHojaSig'));
    }

    public function store(Request $request)
    {
        $inventario        = Inventario::getInventarioActivo();
        $hoja              = \Request::input('hoja');
        $detalleInventario = $inventario->getDetalleHoja($hoja);
        $cantidadLineas    = $detalleInventario->count();

        $detalleInventario->each(function ($linea) {
            $linea->auditor            = \Request::input('auditor');
            $linea->digitador          = auth()->id();
            $linea->stock_fisico       = \Request::input('stock_fisico_'.$linea->id);
            $linea->hu                 = \Request::input('hu_'.$linea->id);
            $linea->observacion        = \Request::input('observacion_'.$linea->id);
            $linea->fecha_modificacion = \Carbon\Carbon::now();
            $linea->save();
        });

        return redirect()
            ->route('inventario.index', ['hoja' => $hoja])
            ->with('alert_message', trans('inventario.digit_msg_save', compact('cantidadLineas', 'hoja')));
    }
}
