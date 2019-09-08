<?php

namespace App\Helpers;

use Illuminate\Support\Arr;

class ReporteTable
{
    protected static $template = [
        'table_open' => '<table class="table table-bordered table-striped table-hover table-condensed reporte" '
            .'style="white-space:nowrap;">',
        'table_close' => '</table>',
        'thead_open' => '<thead class="header">',
        'thead_close' => '</thead>',
        'tbody_open' => '<tbody>',
        'tbody_close' => '</tbody>',
        'row_open' => '<tr>',
        'row_close' => '</tr>',
        'head_open' => '<th>',
        'head_close' => '</th>',
        'data_open' => '<td>',
        'data_close' => '</td>',
        'tfoot_open' => '<tfoot>',
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

    public static function table($data, $campos)
    {
        $reporteOrigen = static::filterCampos($data, $campos);
        $template = static::$template;

        $reporteHead = collect($campos)->reduce(function ($carry, $elem) use ($template) {
                return $carry.$template['head_open'].$elem.$template['head_close'];
        }, '');

        $reporteBody = $reporteOrigen->reduce(function ($carry, $elem) use ($campos, $template) {
            return $carry
                .$template['row_open']
                .collect($campos)->map(function ($campo) use ($elem) {
                    return Arr::get($elem, $campo);
                })
                ->reduce(function ($carry2, $elem) use ($template) {
                    return $carry2.$template['data_open'].$elem.$template['data_close'];
                }, '')
                .$template['row_close'];
        }, '');

        return
            $template['table_open']
                .$template['thead_open'].$template['row_open']
                    .$reporteHead
                .$template['row_close'].$template['thead_close']
                .$template['tbody_open']
                    .$reporteBody
                .$template['tbody_close']
            .$template['table_close'];
    }
}
