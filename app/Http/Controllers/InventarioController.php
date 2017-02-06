<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inventario;
use App\Auditor;
use App\DetalleInventario;
use App\Catalogo;

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
