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
    /**
     * Despliega una hoja de toma de inventarios
     *
     * @return view
     */
    public function showHoja($hoja = null)
    {
        $hoja = empty($hoja) ? 1 : $hoja;

        $inventario = Inventario::getInventarioActivo();
        $detalleInventario = $inventario->getDetalleHoja($hoja);

        $linkAnt = route('inventario.showHoja', ['hoja' => ($hoja > 1) ? $hoja - 1 : 1]);
        $linkSig = route('inventario.showHoja', ['hoja' => $hoja + 1]);

        return view(
            'inventario.inventario',
            compact('inventario', 'detalleInventario', 'hoja', 'linkAnt', 'linkSig')
        );
    }

    /**
     * Actualiza los datos de una hoja de inventario
     *
     * @param  DigitacionRequest $request Request con datos de hoa de toma de inventarios
     * @return redirect
     */
    public function updateHoja(DigitacionRequest $request)
    {
        $hoja = $request->input('hoja');
        $cantidadLineas = Inventario::getInventarioActivo()
            ->updateDetalleHoja($request->input('detalle'), $request->input('auditor'));

        return redirect()
            ->route('inventario.showHoja', compact('hoja'))
            ->with('alert_message', trans('inventario.digit_msg_save', compact('cantidadLineas', 'hoja')));
    }

    /**
     * Despliega formulario para agregar o editar una linea de inventario
     *
     * @param integer $hoja Numero de la hoja para agregar la nueva linea
     * @param integer $id   ID de la linea de inventario a editar
     * @return view
     */
    public function addLinea($hoja = null, $id = null)
    {
        $detalleInventario = DetalleInventario::findOrNew($id);
        $catalogos = $id ? [$detalleInventario->catalogo => $detalleInventario->descripcion] : [];

        return view('inventario.editar', compact('detalleInventario', 'hoja', 'catalogos'));
    }

    /**
     * Persiste los datos de una linea de inventario
     *
     * @param  EditarRequest $request Request con los datos de la linea
     * @param  integer       $hoja    Hoja del inventario a agregar la linea
     * @param  integer       $id      ID de la linea de inventario a editar
     * @return redirect
     */
    public function editLinea(EditarRequest $request, $hoja = null, $id = null)
    {
        $detalleInventario = DetalleInventario::findOrNew($id)->editarLinea($request);

        return redirect()
            ->route('inventario.showHoja', compact('hoja'))
            ->with('alert_message', trans('inventario.digit_msg_add', compact('hoja')));
    }

    /**
     * Eliminar una linea de inventario
     *
     * @param  Request $request Request con los datos de la linea de inventario
     * @param  integer $hoja    Hoja del inventario a eliminar el detalle
     * @param  integer $id      ID del detalle de inventario a eliminar
     * @return redirect
     */
    public function destroyLinea(Request $request, $hoja = null, $id = null)
    {
        DetalleInventario::destroy($id);

        return redirect()
            ->route('inventario.showHoja', ['hoja' => $hoja])
            ->with('alert_message', trans('inventario.digit_msg_delete', compact('id', 'hoja')));
    }

    /**
     * Recupera catalogos de acuerdo a un filtro
     *
     * @param  string $filtro Texto para fitrar los catalogos
     * @return string         Catalogos formateados como options
     */
    public function ajaxCatalogos($filtro = null)
    {
        return Catalogo::getModelAjaxFormOptions([['descripcion', 'like', '%'.$filtro.'%']]);
    }
}
