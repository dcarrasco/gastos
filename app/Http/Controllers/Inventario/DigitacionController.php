<?php

namespace App\Http\Controllers\Inventario;

use Illuminate\Http\Request;
use App\Inventario\Catalogo;
use App\Inventario\Inventario;
use App\Http\Controllers\Controller;
use App\Inventario\DetalleInventario;
use App\Http\Requests\Inventario\EditarRequest;
use App\Http\Requests\Inventario\DigitacionRequest;

class DigitacionController extends Controller
{
    public function showHoja()
    {
        $inventario = Inventario::getInventarioActivo();
        $hoja       = empty(request('hoja', 1)) ? 1 : request('hoja', 1);
        $detalleInventario = $inventario->getDetalleHoja($hoja);

        $linkHojaAnt = route('inventario.showHoja', ['hoja' => ($hoja > 1) ? $hoja - 1 : 1]);
        $linkHojaSig = route('inventario.showHoja', ['hoja' => $hoja + 1]);

        return view(
            'inventario.inventario',
            compact('inventario', 'detalleInventario', 'hoja', 'linkHojaAnt', 'linkHojaSig')
        );
    }

    public function updateHoja(DigitacionRequest $request)
    {
        $inventario = Inventario::getInventarioActivo();
        $hoja       = request('hoja');
        $detalleInventario = $inventario->getDetalleHoja($hoja);

        $detalleInventario->each(function ($linea) {
            $linea->auditor      = request('auditor');
            $linea->digitador    = auth()->id();
            $linea->stock_fisico = request("detalle.{$linea->id}.stock_fisico");
            $linea->hu           = request("detalle.{$linea->id}.hu");
            $linea->observacion  = request("detalle.{$linea->id}.observacion");
            $linea->fecha_modificacion = \Carbon\Carbon::now();
            $linea->save();
        });

        $cantidadLineas = $detalleInventario->count();

        return redirect()
            ->route('inventario.showHoja', ['hoja' => $hoja])
            ->with('alert_message', trans('inventario.digit_msg_save', compact('cantidadLineas', 'hoja')));
    }

    public function addLinea($hoja = null, $id = null)
    {
        $detalleInventario = $id ? DetalleInventario::find($id) : new DetalleInventario;
        $catalogos = $id ? [$detalleInventario->catalogo => $detalleInventario->descripcion] : [];

        return view('inventario.editar', compact('detalleInventario', 'hoja', 'catalogos'));
    }

    public function editLinea(EditarRequest $request, $hoja = null, $id = null)
    {
        $detalleInventario = new DetalleInventario;
        $inventario     = Inventario::getInventarioActivo();
        $hojaInventario = $inventario->getDetalleHoja($hoja);

        // recupera o crea nuevo registro de detalle y puebla modificaciones desde request
        $detalleInventario = $id ? DetalleInventario::find($id) : new DetalleInventario;
        $detalleInventario->fill($request->all());

        // modifica campos que no dependen del request y graba linea de detalle
        $detalleInventario->id_inventario = $inventario->id;
        $detalleInventario->descripcion = Catalogo::find($detalleInventario->catalogo)->descripcion;
        $detalleInventario->stock_sap = 0;
        $detalleInventario->digitador = auth()->id();
        $detalleInventario->auditor = $hojaInventario->first()->auditor;
        $detalleInventario->hoja = $hoja;
        $detalleInventario->reg_nuevo = 'S';
        $detalleInventario->fecha_modificacion = \Carbon\Carbon::now();
        $detalleInventario->save();

        return redirect()
            ->route('inventario.showHoja', ['hoja' => $hoja])
            ->with('alert_message', trans('inventario.digit_msg_add', ['hoja' => $hoja]));
    }

    public function destroyLinea(Request $request, $hoja = null, $id = null)
    {
        DetalleInventario::destroy($id);

        return redirect()
            ->route('inventario.showHoja', ['hoja' => $hoja])
            ->with('alert_message', trans('inventario.digit_msg_delete', compact('id', 'hoja')));
    }

    public function ajaxCatalogos($filtro = null)
    {
        return Catalogo::getModelAjaxFormOptions([['descripcion', 'like', '%'.$filtro.'%']]);
    }
}
