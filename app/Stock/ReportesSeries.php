<?php

namespace App\Stock;

use App\Helpers\Reporte;
use App\Stock\MovimientoSap;

trait ReportesSeries
{
    protected static $template = [
        'table_open'  => '<table class="table table-bordered table-striped table-hover table-condensed reporte">',
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
        return $data->map(function($elem) use ($campos) {
            return collect($elem)
                ->filter(function($elem, $key) use ($campos) {
                    return in_array($key, $campos);
                })->all();
        });
    }

    protected static function reporte($data, $campos)
    {
        $reporteOrigen = static::filterCampos($data, $campos);

        $reporteHead = collect(array_keys($reporteOrigen->first()))
            ->reduce(function($carry, $elem) {
                return $carry.'<th>'.$elem.'</th>';
            }, '');

        $row_open = static::$template['row_open'];
        $row_close = static::$template['row_close'];

        $reporteBody = $reporteOrigen->reduce(function($carry, $elem) use ($row_open, $row_close) {
            return $carry.$row_open
                .collect($elem)->reduce(function($carry2, $elem) {
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

    public static function reporteMovimientos($serie = '')
    {
        $campos = ['serie', 'fec_entrada_doc', 'ce', 'alm', 'rec', 'cmv', 'codigo_sap', 'texto_breve_material', 'lote', 'n_doc', 'referencia', 'usuario'];

        $movimientos = MovimientoSap::where('serie', '2')->get();

        return static::reporte($movimientos, $campos);
    }
}
