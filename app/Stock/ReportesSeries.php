<?php

namespace App\Stock;

use App\Helpers\ReporteTable;
use App\Stock\ReportesSeriesData;

trait ReportesSeries
{
    use ReportesSeriesData;

    protected static function listToArray($series = string, $tipo = 'SAP')
    {
        $series = str_replace(' ', '', $series);
        $arrSeries = preg_grep('/[\d]+/', explode("\r\n", $series));

        $arrSeriesCelular = array();

        foreach ($arrSeries as $llave => $valor) {
            $serieTemp = $valor;

            if ($tipo === 'SAP') {
                // Modificaciones de formato SAP
                $serieTemp = preg_replace('/^01/', '1', $serieTemp);
                $arrSeries[$llave] = (strlen($serieTemp) === 19) ? substr($serieTemp, 1, 18) : $serieTemp;
            } elseif ($tipo === 'trafico') {
                // Modificaciones de formato tabla trafico
                $serieTemp = preg_replace('/^1/', '01', $serieTemp);
                $arrSeries[$llave] = substr($serieTemp, 0, 14).'0';
            } elseif ($tipo === 'SCL') {
                // Modificaciones de formato SCL
                $serieTemp = preg_replace('/^1/', '01', $serieTemp);
                $arrSeries[$llave] = $serieTemp;
            } elseif ($tipo === 'celular') {
                $arrSeries[$llave] = (strlen($valor) === '9') ? substr($valor, 1, 8) : $valor;
                $arrSeriesCelular[$llave] = '9'.$arrSeries[$llave];
            }
        }

        $arrSeries = ($tipo === 'celular') ? array_merge($arrSeries, $arrSeriesCelular) : $arrSeries;

        return collect($arrSeries);
    }

    public static function reporteMovimientos($series = '')
    {
        $campos = [
            'serie',
            'fec_entrada_doc',
            'ce',
            'alm',
            'des_alm',
            'rec',
            'des_rec',
            'cmv',
            'des_cmv',
            'codigo_sap',
            'texto_breve_material',
            'lote',
            'n_doc',
            'referencia',
            'usuario',
            'nom_usuario'
        ];

        return static::listToArray($series)
            ->map(function ($serie) {
                // Para cada una de las series, recupera los movimientos....
                return static::getDataReporteMovimientos($serie);
            })->reduce(function ($carry, $movimientos) use ($campos) {
                // ...y luego junta los reportes de cada uno
                return $carry.ReporteTable::table($movimientos, $campos);
            }, '');
    }

    public static function reporteDespachos($series = '')
    {
        $campos = [
            'serie',
            'cod_sap',
            'texto_breve_material',
            'lote',
            'operador',
            'fecha',
            'cmv',
            'alm',
            'rec',
            'des_bodega',
            'rut',
            'tipo_servicio',
            'icc',
            'abonado',
            'n_doc',
            'referencia'
        ];

        return ReporteTable::table(static::getDataReporteDespachos(static::listToArray($series)), $campos);
    }

    public static function reporteStockSAP($series = '')
    {
        $campos = [
            'fecha_stock',
            'serie',
            'material',
            'des_material',
            'centro',
            'almacen',
            'des_almacen',
            'lote',
            'status_sistema',
            'estado_stock',
            'modif_el',
            'modificado_por',
            'nom_usuario'
        ];

        return ReporteTable::table(static::getDataReporteStockSAP(static::listToArray($series)), $campos);
    }

    public static function reporteStockSCL($series = '')
    {
        $campos = [
            'fecha',
            'serie_sap',
            'cod_bodega',
            'tip_bodega',
            'cod_articulo',
            'tip_stock',
            'cod_uso',
            'cod_estado'
        ];

        return ReporteTable::table(static::getDataReporteStockSCL(static::listToArray($series)), $campos);
    }
}
