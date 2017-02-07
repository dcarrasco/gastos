<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Inventario;
use App\DetalleInventario;
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

    public function ajaxCatalogos($filtro = null)
    {
        $catalogo = new Catalogo;

        return $catalogo->getModelAjaxFormOptions($filtro);
    }

    public function add($hoja = null, $id = null)
    {
        $detalleInventario = $id ? DetalleInventario::find($id) : new DetalleInventario;
        $catalogos = $id ? [$detalleInventario->catalogo => $detalleInventario->descripcion] : [];

        return view('inventario.editar', compact('detalleInventario', 'hoja', 'catalogos'));
    }

    public function edit(Request $request, $hoja = null, $id = null)
    {
        $detalleInventario = new DetalleInventario;
        $this->validate($request, $detalleInventario->getIngresoInventarioValidation());

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
            ->route('inventario.index', ['hoja' => $hoja])
            ->with('alert_message', trans('inventario.digit_msg_add', ['hoja' => $hoja]));;
    }

    public function destroy(Request $request, $hoja = null, $id = null)
    {
        DetalleInventario::destroy($id);

        return redirect()
            ->route('inventario.index', ['hoja' => $hoja])
            ->with('alert_message', trans('inventario.digit_msg_delete', compact('id', 'hoja')));
    }
}
