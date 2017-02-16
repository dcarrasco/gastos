<?php

namespace App\Stock;

use App\OrmModel;

class StockSapMovil extends OrmModel
{
    protected static $tipoMaterial = "CASE WHEN (substring(cod_articulo,1,8)='PKGCLOTK' OR substring(cod_articulo,1,2)='TS') THEN 'SIMCARD' WHEN substring(cod_articulo, 1,2) in ('TM','TO','TC','PK','PO') THEN 'EQUIPOS' ELSE 'OTROS' END";

    protected static $sumCantidad = 'sum(libre_utilizacion + bloqueado + contro_calidad + transito_traslado + otros)';

    protected static $sumValor = 'sum(val_lu + val_bq + val_cq + val_tt + val_ot)';

    protected $dates = ['fecha_stock'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = \DB::raw(config('invfija.bd_stock_movil').' as s');
    }

    public static function fechasStock($tipoFecha = 'ultdia')
    {
        $fechas = \DB::table(config('invfija.bd_stock_movil_fechas'))
            ->orderBy('fecha_stock', 'desc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [fmt_fecha_db($item->fecha_stock) => fmt_fecha($item->fecha_stock)];
            })
            ->all();

        if ($tipoFecha === 'ultdia') {
            $fechasUltDia = [];
            $ultMes = '';
            foreach ($fechas as $key => $item) {
                if (substr($key, 0, 6) !== $ultMes) {
                    $fechasUltDia[$key] = $item;
                }
                $ultMes = substr($key, 0, 6);
            }

            return $fechasUltDia;
        }

        return $fechas;
    }

    public static function getStock()
    {
        if (empty(request()->input('fecha')) or empty(request()->input('almacenes'))) {
            return;
        }

        $stock = static::whereIn('fecha_stock', request()->input('fecha'))
            ->filtroTipoAlmacen(request()->input('almacenes'))
            ->select([
                's.fecha_stock',
                't.tipo',
                \DB::raw(static::$tipoMaterial.' as tipo_material'),
                \DB::raw(static::$sumCantidad.' as cant'),
                \DB::raw(static::$sumValor.' as valor'),
            ])
            ->groupBy(['s.fecha_stock', 't.tipo', \DB::raw(static::$tipoMaterial)])
            ->get();

        return $stock;
    }

    public function scopeFiltroTipoAlmacen($query, $tiposAlmacen = [])
    {
        if (empty($tiposAlmacen)) {
            return $query;
        }

        $query = $query
            ->leftJoin(\DB::raw(config('invfija.bd_almacenes_sap').' as a'), function ($join) {
                $join->on('s.centro', '=', 'a.centro');
                $join->on('s.cod_bodega', '=', 'a.cod_almacen');
            })
            ->leftJoin(\DB::raw(config('invfija.bd_tipoalmacen_sap').' as ta'), function ($join) {
                $join->on('s.centro', '=', 'ta.centro');
                $join->on('s.cod_bodega', '=', 'ta.cod_almacen');
            })
            ->leftJoin(\DB::raw(config('invfija.bd_tiposalm_sap').' as t'), 't.id_tipo', '=', 'ta.id_tipo')
            ->whereIn('t.id_tipo', $tiposAlmacen);

        return $query;
    }
}
