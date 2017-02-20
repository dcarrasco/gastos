<?php

namespace App\Toa;

use App\Stock\ClaseMovimiento;
use App\Helpers\Reporte;

class Consumos
{
    const CENTROS_CONSUMO = ['CH32', 'CH33'];

    public static function getReporte($reporte = null, $fechaDesde = null, $fechaHasta)
    {
        $datosReporte  = static::{'getDataReporte'.ucfirst($reporte)}($fechaDesde, $fechaHasta);
        $camposReporte = static::{'getCamposReporte'.ucfirst($reporte)}($fechaDesde, $fechaHasta);

        $reporte = new Reporte($datosReporte, $camposReporte);

        return $reporte->make();
    }

    protected static function getDataReporteCiudades($fechaDesde, $fechaHasta)
    {
        $from = \DB::table(\DB::raw(config('invfija.bd_movimientos_sap_fija').' m'))
            ->where('m.fecha_contabilizacion', '>=', $fechaDesde)
            ->where('m.fecha_contabilizacion', '<=', $fechaHasta)
            ->whereIn('m.codigo_movimiento', ClaseMovimiento::transaccionesConsumoToa())
            ->whereIn('centro', static::CENTROS_CONSUMO)
            ->leftJoin(\DB::raw(config('invfija.bd_tecnicos_toa').' b'), \DB::raw('m.cliente collate Latin1_General_CI_AS'), '=', \DB::raw('b.id_tecnico collate Latin1_General_CI_AS'))
            ->leftJoin(\DB::raw(config('invfija.bd_ciudades_toa').' d'), 'b.id_ciudad', '=', 'd.id_ciudad')
            ->select(['b.id_ciudad', 'd.ciudad', 'd.orden', 'm.referencia', \DB::raw('sum(-m.cantidad_en_um) as cant'), \DB::raw('sum(-m.importe_ml) as monto')])
            ->groupBy(['b.id_ciudad', 'd.ciudad', 'd.orden', 'm.referencia']);

        return \DB::table(\DB::raw('('.$from->toSql().') q1'))
            ->mergeBindings($from)
            ->select([
                'q1.id_ciudad',
                'q1.ciudad',
                'q1.orden',
                \DB::raw('\'ver peticiones\' as texto_link'),
                \DB::raw('count(q1.referencia) as referencia'),
                \DB::raw('sum(q1.cant) as cant'),
                \DB::raw('sum(monto) as monto'),
            ])
            ->groupBy(['q1.id_ciudad', 'q1.ciudad', 'q1.orden'])
            ->orderBy('q1.orden')
            ->get();
    }

    protected static function getCamposReporteCiudades($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'ciudad'     => ['titulo' => 'Ciudad', 'tipo' => 'texto'],
            'referencia' => ['titulo' => 'Peticiones', 'tipo' => 'numero', 'class' => 'text-right'],
            'cant'       => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto'      => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => ['titulo' => '', 'tipo' => 'link_registro', 'class' => 'text-right', 'href' => 'toa_consumos/ver_peticiones/ciudades/'.$fechaDesde.'/'.$fechaHasta, 'href_registros' => ['id_ciudad']],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }
}
