<?php

namespace App\Stock;

trait ReportesSeriesData
{
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

    public static function getDataReporteDespachos($series = [])
    {
        return \DB::table(\DB::raw(config('invfija.bd_despachos_sap').' as d'))
            ->whereIn('n_serie', $series)
            ->get();
    }

    public static function getDataReporteStockSAP($series = [])
    {
        return \DB::table(\DB::raw(config('invfija.bd_stock_seriado_sap').' as s'))
            ->whereIn('serie', $series)
            ->leftJoin(\DB::raw(config('invfija.bd_almacenes_sap').' as a'), function ($join) {
                $join->on('s.almacen', '=', 'a.cod_almacen');
                $join->on('s.centro', '=', 'a.centro');
            })
            ->leftJoin(\DB::raw(config('invfija.bd_usuarios_sap').' as u'), 's.modificado_por', '=', 'u.usuario')
            ->get();
    }

    public static function getDataReporteStockSCL($series = [])
    {
        return \DB::table(\DB::raw(config('invfija.bd_stock_scl').' as s'))
            ->whereIn('serie_sap', $series)
            ->get();
    }
}
