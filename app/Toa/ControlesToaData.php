<?php

namespace App\Toa;

trait ControlesToaData
{
    public static function getControlTecnicosData($empresa = null, $fechaDesde = null, $fechaHasta = null, $peticiones = null, $selectDato = null)
    {
        $select = ['a.fecha', 'a.tecnico', \DB::raw('sum('.$selectDato.') as dato')];
        $groupBy = ['a.fecha', 'a.tecnico'];

        return \DB::table(\DB::raw(config('invfija.bd_peticiones_sap').' a'))
            ->leftJoin(\DB::raw(config('invfija.bd_tecnicos_toa').' b'), 'a.tecnico', '=', 'b.id_tecnico')
            ->where('a.fecha', '>=', $fechaDesde)
            ->where('a.fecha', '<', $fechaHasta)
            ->where('b.id_empresa', $empresa)
            ->select($select)
            ->groupBy($groupBy)
            ->get();
    }

    public static function getControlMaterialesData($empresa = null, $fechaDesde = null, $fechaHasta = null, $filtroTrx = null, $selectDato = null)
    {
        $datoDesplegar = [
            'unidades'   => 'sum(-a.cantidad_en_um)',
            'monto'      => 'sum(-a.importe_ml)'
        ];

        return \DB::table(\DB::raw(config('invfija.bd_movimientos_sap_fija').' a'))
            ->leftJoin(\DB::raw(config('invfija.bd_tecnicos_toa').' b'), 'a.cliente', '=', 'b.id_tecnico')
            ->leftJoin(\DB::raw(config('invfija.bd_catalogos').' c'), 'a.material', '=', 'c.catalogo')
            ->leftJoin(\DB::raw(config('invfija.bd_catalogo_tip_material_toa').' d'), 'a.material', '=', 'd.id_catalogo')
            ->leftJoin(\DB::raw(config('invfija.bd_tip_material_trabajo_toa').' e'), 'd.id_tip_material_trabajo', '=', 'e.id')
            ->where('a.fecha_contabilizacion', '>=', $fechaDesde)
            ->where('a.fecha_contabilizacion', '<', $fechaHasta)
            ->where('b.id_empresa', $empresa)
            ->whereIn('a.codigo_movimiento', $filtroTrx)
            ->select(['a.fecha_contabilizacion', 'a.material', 'c.descripcion', 'a.ume', 'e.desc_tip_material', \DB::raw($datoDesplegar[$selectDato].' as dato')])
            ->groupBy(['a.fecha_contabilizacion', 'a.material', 'c.descripcion', 'a.ume', 'e.desc_tip_material'])
            ->get();
    }
}
