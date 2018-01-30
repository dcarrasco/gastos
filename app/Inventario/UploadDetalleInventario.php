<?php

namespace App\Inventario;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Inventario\Inventario;
use App\Http\Controllers\Controller;
use App\Inventario\DetalleInventario;

trait UploadDetalleInventario
{

    protected static function cargarDatosUpload($request, $inventario)
    {
        $numLinea   = 0;
        $countOK    = 0;
        $countError = 0;
        $lineasError  = [];
        $bulkInsert   = [];
        $script       = '';

        foreach (file($request->file('upload_file')->getRealPath()) as $linea) {
            $numLinea += 1;
            $resultadoProcesaLinea = static::procesaLinea($linea, $inventario);

            if ($resultadoProcesaLinea === 'no_procesar') {
                // no se procesa esta linea
            } elseif ($resultadoProcesaLinea === 'error') {
                $countError += 1;
                array_push($lineasError, $numLinea);
            } else {
                $countOK += 1;
                if (is_array($resultadoProcesaLinea)) {
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
            'script' => $script,
            'regsOK' => $countOK,
            'regsError' => $countError,
            'msjTermino' => $msjTermino
        ];
    }

    protected static function procesaLinea($linea = '', $inventario)
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
            ['ubicacion', 'catalogo', 'descripcion', 'lote', 'centro', 'almacen', 'um', 'stock_sap', 'hoja'],
            $datos
        );
        extract($datos);

        if (strtoupper($ubicacion) === 'UBICACION' or strtoupper($catalogo) === 'CATALOGO' or
            strtoupper($descripcion) === 'DESCRIPCION' or
            strtoupper($centro) === 'CENTRO' or strtoupper($almacen) === 'ALMACEN' or
            strtoupper($lote) === 'LOTE' or strtoupper($um) === 'UM' or
            strtoupper($stock_sap) === 'STOCK_SAP' or strtoupper($hoja) === 'HOJA'
            // or $hu === 'HU'
            ) {
            // cabecera del archivo, no se hace nada
            return 'no_procesar';
        } else {
            if (is_numeric($stock_sap) and is_numeric($hoja)) {
                return [
                    '_token' => csrf_token(),
                    'id' => 0,
                    'id_inventario' => $inventario->id,
                    'hoja' => $hoja,
                    'ubicacion' => $ubicacion,
                    'hu' => '',
                    'catalogo' => $catalogo,
                    'descripcion' => $descripcion,
                    'lote' => $lote,
                    'centro' => $centro,
                    'almacen' => $almacen,
                    'um' => $um,
                    'stock_sap' => $stock_sap,
                    'stock_fisico' => 0,
                    'digitador' => 0,
                    'auditor' => 0,
                    'observacion' => '',
                    'fecha_modificacion' => Carbon::now()->__toString(),
                    'reg_nuevo' => '',
                    'stock_ajuste' => 0,
                    'glosa_ajuste' => '',
                ];
            } else {
                // error: stock y/o hoja no son numericos
                return 'error';
            }
        }
    }
}
