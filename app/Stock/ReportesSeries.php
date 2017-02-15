<?php

namespace App\Stock;

use App\Helpers\Reporte;
use App\Stock\MovimientoSap;

trait ReportesSeries
{
    protected static $template = [
        'table_open'  => '<table class="table table-bordered table-striped table-hover table-condensed reporte" style="white-space:nowrap;">',
        'table_close' => '</table>',
        'thead_open'  => '<thead class="header">',
        'thead_close' => '</thead>',
        'tbody_open'  => '<tbody>',
        'tbody_close' => '</tbody>',
        'row_open'    => '<tr>',
        'row_close'   => '</tr>',
        'tfoot_open'  => '<tfoot>',
        'tfoot_close' => '</tfoot>',
    ];

    protected static function filterCampos($data, $campos)
    {
        return $data->map(function ($elem) use ($campos) {
            return collect($elem)
                ->filter(function ($elem, $key) use ($campos) {
                    return in_array($key, $campos);
                })->all();
        });
    }

    protected static function reporte($data, $campos)
    {
        $reporteOrigen = static::filterCampos($data, $campos);

        $row_open = static::$template['row_open'];
        $row_close = static::$template['row_close'];

        $reporteHead = collect($campos)->reduce(function ($carry, $elem) {
                return $carry.'<th>'.$elem.'</th>';
        }, '');

        $reporteBody = $reporteOrigen->reduce(function ($carry, $elem) use ($row_open, $row_close, $campos) {
            return $carry
                .$row_open
                .collect($campos)->map(function ($campo) use ($elem) {
                    return array_get($elem, $campo);
                })->reduce(function ($carry2, $elem) {
                    return $carry2.'<td>'.$elem.'</td>';
                }, '')
                .$row_close;
        }, '');

        return
            static::$template['table_open']
                .static::$template['thead_open'].static::$template['row_open']
                    .$reporteHead
                .static::$template['row_close'].static::$template['thead_close']
                .static::$template['tbody_open']
                    .$reporteBody
                .static::$template['tbody_close']
            .static::$template['table_close'];
    }

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
        $campos = ['serie', 'fec_entrada_doc', 'ce', 'alm', 'des_alm', 'rec', 'des_rec', 'cmv', 'des_cmv', 'codigo_sap', 'texto_breve_material', 'lote', 'n_doc', 'referencia', 'usuario', 'nom_usuario'];

        return static::listToArray($series, 'SAP')
            ->map(function ($serie) {
                // Para cada una de las series, recupera los movimientos....
                return static::getDataReporteMovimientos($serie);
            })->reduce(function ($carry, $movimientos) use ($campos) {
                // ...y luego junta los reportes de cada uno
                return $carry.static::reporte($movimientos, $campos);
            }, '');
    }

    public static function getDataReporteMovimientos($serie = '')
    {
        return \DB::table(\DB::raw(config('invfija.bd_movimientos_sap').' as m'))
            ->select(['m.*', 'nom_usuario', 'a1.des_almacen as des_alm', 'a2.des_almacen as des_rec', 'des_cmv'])
            ->where('m.serie', $serie)
            ->leftJoin(\DB::raw(config('invfija.bd_usuarios_sap').' as u'), 'm.usuario', '=', 'u.usuario')
            ->leftJoin(\DB::raw(config('invfija.bd_almacenes_sap').' as a1'), function ($join) {
                $join->on('m.alm', '=', 'a1.cod_almacen');
                $join->on('m.ce', '=', 'a1.centro');
            })
            ->leftJoin(\DB::raw(config('invfija.bd_almacenes_sap').' as a2'), function ($join) {
                $join->on('m.rec', '=', 'a2.cod_almacen');
                $join->on('m.ce', '=', 'a2.centro');
            })
            ->leftJoin(\DB::raw(config('invfija.bd_cmv_sap').' as c'), 'm.cmv', '=', 'c.cmv')
            ->get();
    }

    public static function reporteDespachos($series = '')
    {
        $campos = ['serie', 'fec_entrada_doc', 'ce', 'alm', 'des_alm', 'rec', 'des_rec', 'cmv', 'des_cmv', 'codigo_sap', 'texto_breve_material', 'lote', 'n_doc', 'referencia', 'usuario', 'nom_usuario'];

        return static::listToArray($series, 'SAP')
            ->map(function ($serie) {
                // Para cada una de las series, recupera los movimientos....
                return static::getDataReporteMovimientos($serie);
            })->reduce(function ($carry, $movimientos) use ($campos) {
                // ...y luego junta los reportes de cada uno
                return $carry.static::reporte($movimientos, $campos);
            }, '');
    }

    public static function getDataReporteDespachos($serie = '')
    {
        return \DB::table(\DB::raw(config('invfija.bd_movimientos_sap').' as m'))
            ->select(['m.*', 'nom_usuario', 'a1.des_almacen as des_alm', 'a2.des_almacen as des_rec', 'des_cmv'])
            ->where('m.serie', $serie)
            ->leftJoin(\DB::raw(config('invfija.bd_usuarios_sap').' as u'), 'm.usuario', '=', 'u.usuario')
            ->leftJoin(\DB::raw(config('invfija.bd_almacenes_sap').' as a1'), function ($join) {
                $join->on('m.alm', '=', 'a1.cod_almacen');
                $join->on('m.ce', '=', 'a1.centro');
            })
            ->leftJoin(\DB::raw(config('invfija.bd_almacenes_sap').' as a2'), function ($join) {
                $join->on('m.rec', '=', 'a2.cod_almacen');
                $join->on('m.ce', '=', 'a2.centro');
            })
            ->leftJoin(\DB::raw(config('invfija.bd_cmv_sap').' as c'), 'm.cmv', '=', 'c.cmv')
            ->get();
    }
}
