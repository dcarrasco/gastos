<?php

namespace App\Http\Controllers\Inventario;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Inventario\Inventario;
use App\Http\Controllers\Controller;
use App\Inventario\DetalleInventario;
use App\Http\Controllers\Inventario\ModulosAjustes;
use App\Http\Requests\Inventario\UploadDetalleInventarioRequest;

class SubirStockController extends Controller
{
    use ModulosAjustes;

    protected $inventario = null;

    public function upload(Request $request)
    {
        $moduloSelected = 'subir-stock';
        $this->inventario = Inventario::getInventarioActivo();
        $inventario = $this->inventario;
        $showScriptCarga = false;
        $scriptCarga = '';

        if ($request->hasFile('upload_file') and $request->file('upload_file')->isValid()) {
            $inventario->lineas()->delete();
            $resultado = DetalleInventario::cargarDatosUpload($request, $inventario);

            $showScriptCarga = true;
            $scriptCarga = $resultado['script'];
            $regsOK      = $resultado['regsOK'];
            $regsError   = $resultado['regsError'];
            $msjError    = ($regsError > 0)
                ? '<br><div class="error round">' . $resultado['msjTermino'] . '</div>'
                : '';
        }

        return view('inventario.sube_stock', compact('showScriptCarga', 'inventario', 'moduloSelected', 'msjError', 'scriptCarga', 'regsOK'));
    }

    public function uploadLinea(UploadDetalleInventarioRequest $request)
    {
        DetalleInventario::create($request->all());
    }

}
