<?php

namespace App\Stock;

use App\OrmModel;

class StockSapMovil extends OrmModel
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = \DB::raw(config('invfija.bd_stock_movil_res01').' as s');
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
        $stock = static::whereIn('fecha_stock', request()->input('fecha'))
            ->filtroTipoAlmacen(request()->input('almacenes'))
            ->first();

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
            ->leftJoin(\DB::raw(config('invfija.bd_tipoalmacen_sap').' as t'), function ($join) {
                $join->on('s.centro', '=', 't.centro');
                $join->on('s.cod_bodega', '=', 't.cod_almacen');
            })
            ->whereIn('t.id_tipo', $tiposAlmacen);

        return $query;
    }
}
