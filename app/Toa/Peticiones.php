<?php

namespace App\Toa;

use App\Stock\ClaseMovimiento;
use App\Helpers\Reporte;

class Peticiones
{
    const CENTROS_CONSUMO = ['CH32', 'CH33'];

    public static function getReporte($data)
    {
        $reporte = new Reporte($data, static::getCamposReporte());

        return $reporte->make();
    }

    public static function getPeticiones($nombreReporte, $fechaDesde, $fechaHasta, $id)
    {
        return static::{'getDataReporte'.ucfirst($nombreReporte)}($fechaDesde, $fechaHasta, $id);
    }

    protected static function getDataReporteBase($fechaDesde, $fechaHasta)
    {
        $select = [
            \DB::raw('min(m.fecha_contabilizacion) as fecha'),
            'm.referencia',
            'm.carta_porte',
            'c.empresa',
            'm.cliente',
            'b.tecnico',
            'd.acoord_x',
            'd.acoord_y',
            \DB::raw("'ver detalle' as texto_link"),
            \DB::raw('sum(-m.cantidad_en_um) as cant'),
            \DB::raw('sum(-m.importe_ml) as monto'),
        ];

        $groupBy = [
            'm.referencia',
            'm.carta_porte',
            'c.empresa',
            'm.cliente',
            'b.tecnico',
            'd.acoord_x',
            'd.acoord_y',
        ];

        return \DB::table(\DB::raw(config('invfija.bd_movimientos_sap_fija').' m'))
            ->leftJoin(\DB::raw(config('invfija.bd_tecnicos_toa').' b'), \DB::raw('m.cliente collate Latin1_General_CI_AS'), '=', \DB::raw('b.id_tecnico collate Latin1_General_CI_AS'))
            ->leftJoin(\DB::raw(config('invfija.bd_empresas_toa').' c'), \DB::raw('m.vale_acomp collate Latin1_General_CI_AS'), '=', \DB::raw('c.id_empresa collate Latin1_General_CI_AS'))
            ->leftJoin(\DB::raw(config('invfija.bd_peticiones_toa').' d'), function ($join) {
                $join->on('m.referencia', '=', 'd.appt_number');
                $join->on('d.astatus', '=', \DB::raw("'complete'"));
            })
            ->leftJoin(\DB::raw(config('invfija.bd_catalogo_tip_material_toa').' e'), 'm.material', '=', 'e.id_catalogo')
            ->leftJoin(\DB::raw(config('invfija.bd_tip_material_trabajo_toa').' f'), 'e.id_tip_material_trabajo', '=', 'f.id')
            ->where('m.fecha_contabilizacion', '>=', $fechaDesde)
            ->where('m.fecha_contabilizacion', '<=', $fechaHasta)
            ->whereIn('m.codigo_movimiento', ClaseMovimiento::transaccionesConsumoToa())
            ->whereIn('m.centro', static::CENTROS_CONSUMO)
            ->select($select)
            ->groupBy($groupBy)
            ->orderBy('m.referencia');
    }

    protected static function getDataReporteCiudades($fechaDesde, $fechaHasta, $idCiudad)
    {
        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->where('id_ciudad', $idCiudad)
            ->get();
    }

    protected static function getCamposReporte()
    {
        $camposReporte = [
            'referencia'  => ['titulo' => 'Numero peticion', 'tipo' => 'link', 'href' => 'toa_consumos/detalle_peticion/'],
            'fecha'       => ['titulo' => 'Fecha', 'tipo' => 'fecha'],
            'carta_porte' => ['titulo' => 'Tipo trabajo', 'tipo' => 'texto'],
            'empresa'     => ['titulo' => 'Empresa', 'tipo' => 'texto'],
            'cliente'     => ['titulo' => 'Cod Tecnico', 'tipo' => 'texto'],
            'tecnico'     => ['titulo' => 'Nombre Tecnico', 'tipo' => 'texto'],
            'cant'        => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto'       => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
        ];

        Reporte::setOrderCampos($camposReporte, 'referencia');

        return $camposReporte;
    }
}
