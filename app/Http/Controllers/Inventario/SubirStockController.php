<?php

namespace App\Http\Controllers\Inventario;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Inventario\ModulosAjustes;
use Illuminate\Http\Request;
use App\Inventario;
use App\DetalleInventario;

class SubirStockController extends Controller
{
    use ModulosAjustes;

    protected $inventario = null;

    public function showForm()
    {
        $moduloSelected = 'subir-stock';

        $showScriptCarga = false;
        $inventario = Inventario::getInventarioActivo();
        $scriptCarga = '';

        return view('inventario.sube_stock', compact('showScriptCarga', 'inventario', 'moduloSelected', 'scriptCarga'));
    }

    public function upload(Request $request)
    {
        $moduloSelected = 'subir-stock';
        $this->inventario = Inventario::getInventarioActivo();
        $inventario = $this->inventario;

        if ($request->hasFile('upload_file') and $request->file('upload_file')->isValid()) {
            $inventario->lineas()->delete();
            $resultado = $this->cargarDatosUpload($request);
        }

        $showScriptCarga = true;
        $scriptCarga = $resultado['script'];
        $regsOK      = $resultado['regsOK'];
        $regsError   = $resultado['regsError'];
        $msjError    = ($regsError > 0)
            ? '<br><div class="error round">' . $resultado['msjTermino'] . '</div>'
            : '';

        return view('inventario.sube_stock', compact('showScriptCarga', 'inventario', 'moduloSelected', 'msjError', 'scriptCarga', 'regsOK'));
    }

    public function uploadLinea(Request $request)
    {
        DetalleInventario::create($request->all());
    }

    protected function cargarDatosUpload($request)
    {
        $numLinea   = 0;
        $countOK    = 0;
        $countError = 0;
        $lineasError  = [];
        $bulkInsert   = [];
        $script       = '';

        foreach (file($request->file('upload_file')->getRealPath()) as $linea) {
            $numLinea += 1;
            $resultadoProcesaLinea = $this->procesaLinea($linea);

            if ($resultadoProcesaLinea === 'no_procesar') {
                // no se procesa esta linea
            } else if ($resultadoProcesaLinea === 'error') {
                $countError += 1;
                array_push($lineasError, $numLinea);
            } else {
                $countOK += 1;
                if (is_array($resultadoProcesaLinea))
                {
                    $resultadoProcesaLinea['count'] = $numLinea;
                    $script .= 'subeStock.proc_linea_carga('.json_encode($resultadoProcesaLinea).");\n";
                }
            }
        }

        $msjTermino = 'Total lineas: '.($countOK + $countError)." (OK: {$countOK}; Error:{$countError})";

        if ($countError > 0) {
            $msjTermino .= '<br>Lineas con errores ('.implode(', ', $arr_lineas_error).')';
        }

        return [
            'script'     => $script,
            'regsOK'     => $countOK,
            'regsError'  => $countError,
            'msjTermino' => $msjTermino
        ];
    }

    protected function procesaLinea($linea = '')
    {
        $linea = utf8_encode(trim($linea, "\r\n"));
        $linea = str_replace("'", '"', $linea);

        // no error: linea en blanco
        if ($linea === '') {
            return 'no_procesar';
        }

        $datos = explode("\t", $linea);

        // error: linea con cantidad de campos <> 9
        // igual a 10 en caso de tener HU
        if (count($datos) !== 9) {
            return 'error';
        }

        $datos = array_combine(
            array('ubicacion', 'catalogo', 'descripcion', 'lote', 'centro', 'almacen', 'um', 'stock_sap', 'hoja'),
            $datos
        );
        extract($datos);

        if (strtoupper($ubicacion) === 'UBICACION' OR strtoupper($catalogo) === 'CATALOGO' OR
            strtoupper($descripcion) === 'DESCRIPCION' OR
            strtoupper($centro) === 'CENTRO' OR strtoupper($almacen) === 'ALMACEN' OR
            strtoupper($lote) === 'LOTE' OR strtoupper($um) === 'UM' OR
            strtoupper($stock_sap) === 'STOCK_SAP' OR strtoupper($hoja) === 'HOJA'
            // OR $hu === 'HU'
            ) {
            // cabecera del archivo, no se hace nada
            return 'no_procesar';
        } else {
            if (is_numeric($stock_sap) AND is_numeric($hoja)) {
                return [
                    '_token'             => csrf_token(),
                    'id'                 => 0,
                    'id_inventario'      => $this->inventario->id,
                    'hoja'               => $hoja,
                    'ubicacion'          => $ubicacion,
                    'hu'                 => '',
                    'catalogo'           => $catalogo,
                    'descripcion'        => $descripcion,
                    'lote'               => $lote,
                    'centro'             => $centro,
                    'almacen'            => $almacen,
                    'um'                 => $um,
                    'stock_sap'          => $stock_sap,
                    'stock_fisico'       => 0,
                    'digitador'          => 0,
                    'auditor'            => 0,
                    'observacion'        => '',
                    'fecha_modificacion' => \Carbon\Carbon::now()->__toString(),
                    'reg_nuevo'          => '',
                    'stock_ajuste'       => 0,
                    'glosa_ajuste'       => '',
                ];
            } else {
                // error: stock y/o hoja no son numericos
                return 'error';
            }
        }
    }

}
