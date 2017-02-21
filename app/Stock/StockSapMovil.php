<?php

namespace App\Stock;

use App\OrmModel;
use App\Helpers\Reporte;

class StockSapMovil extends OrmModel
{
    protected static $tipoMaterial = "CASE WHEN (substring(cod_articulo,1,8)='PKGCLOTK' OR substring(cod_articulo,1,2)='TS') THEN 'SIMCARD' WHEN substring(cod_articulo, 1,2) in ('TM','TO','TC','PK','PO') THEN 'EQUIPOS' ELSE 'OTROS' END";

    protected static $sumCantidad = 'sum(libre_utilizacion + bloqueado + contro_calidad + transito_traslado + otros)';

    protected static $sumValor = 'sum(val_lu + val_bq + val_cq + val_tt + val_ot)';

    protected static $campos = [
        'fecha_stock' => ['titulo'=>'Fecha', 'tipo'=>'fecha'],
        'tipo' => ['titulo'=>'Tipo almacen'],
        'centro' => ['titulo'=>'Centro'],
        'cod_bodega' => ['titulo'=>'Almacen'],
        'des_almacen' => ['titulo'=>'Desc Almacen'],
        'cod_articulo' => ['titulo'=>'Material'],
        'lote' => ['titulo'=>'Lote'],
        'tipo_material' => ['titulo'=>'Tipo Material'],
        'cant' => ['titulo'=>'Cantidad', 'tipo'=>'numero', 'class'=>'text-right'],
        'valor' => ['titulo'=>'Monto', 'tipo'=>'valor', 'class'=>'text-right'],
    ];

    // protected $dates = ['fecha_stock'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = \DB::raw(config('invfija.bd_stock_movil').' as s');
    }

    public static function todasFechasStock()
    {
        return \DB::table(config('invfija.bd_stock_movil_fechas'))
            ->orderBy('fecha_stock', 'desc')
            ->get();
    }

    public static function ultDiaFechasStock()
    {
        return \DB::table(config('invfija.bd_stock_movil_fechas'))
            ->select([\DB::raw('100*year(fecha_stock)+month(fecha_stock) as mes'), \DB::raw('max(fecha_stock) as fecha_stock')])
            ->groupBy([\DB::raw('100*year(fecha_stock)+month(fecha_stock)')])
            ->orderBy('mes', 'desc')
            ->get();
    }

    public static function fechasStock($tipoFecha = 'ultdia')
    {
        $fechas = ($tipoFecha === 'ultdia') ? static::ultDiaFechasStock() : static::todasFechasStock();

        return $fechas
        ->mapWithKeys(function ($item) {
            return [fmt_fecha_db($item->fecha_stock) => fmt_fecha($item->fecha_stock)];
        })->all();
    }

    public static function getStock()
    {
        if (empty(request('fecha')) or empty(request('almacenes'))) {
            return;
        }

        $selectFields = static::getSelectFields();

        $queryStock = static::whereIn('fecha_stock', request('fecha'));

        if (request('sel_tiposalm') === 'sel_tiposalm') {
            $queryStock = $queryStock->filtroTipoAlmacen(request('almacenes'));
        } elseif (request('sel_tiposalm') === 'sel_almacenes') {
            $queryStock = $queryStock->filtroAlmacen(request('almacenes'));
        }

        $stock = $queryStock
            ->select($selectFields['select'])
            ->groupBy($selectFields['groupBy'])
            ->get();

        $campos = $stock->isEmpty()
            ? []
            : collect($stock->first())->mapWithKeys(function ($elem, $key) {
                return [$key => array_get(static::$campos, $key)];
            })->all();
        Reporte::setOrderCampos($campos, 'fecha');

        $reporte = new Reporte($stock, $campos);

        return $reporte->make();
    }

    protected static function getSelectFields()
    {
        $select  = [];
        $groupBy = [];

        $select[]  = 's.fecha_stock';
        $groupBy[] = 's.fecha_stock';

        if (request('sel_tiposalm') === 'sel_tiposalm') {
            $select[]  = 't.tipo';
            $groupBy[] = 't.tipo';
        }

        if (request('almacen') === 'almacen' or request('sel_tiposalm') === 'sel_almacenes') {
            $select[]  = 's.centro';
            $groupBy[] = 's.centro';
            $select[]  = 's.cod_bodega';
            $groupBy[] = 's.cod_bodega';
            $select[]  = 'a.des_almacen';
            $groupBy[] = 'a.des_almacen';
        }

        if (request('material') === 'material') {
            $select[]  = 's.cod_articulo';
            $groupBy[] = 's.cod_articulo';
        }

        if (request('lote') === 'lote') {
            $select[]  = 's.lote';
            $groupBy[] = 's.lote';
        }

        $select[]  = \DB::raw(static::$tipoMaterial.' as tipo_material');
        $groupBy[] = \DB::raw(static::$tipoMaterial);

        $select[] = \DB::raw(static::$sumCantidad.' as cant');
        $select[] = \DB::raw(static::$sumValor.' as valor');

        return compact('select', 'groupBy');

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

    public function scopeFiltroAlmacen($query, $almacenes = [])
    {
        if (empty($almacenes)) {
            return $query;
        }

        $query = $query
            ->leftJoin(\DB::raw(config('invfija.bd_almacenes_sap').' as a'), function ($join) {
                $join->on('s.centro', '=', 'a.centro');
                $join->on('s.cod_bodega', '=', 'a.cod_almacen');
            })
            ->whereIn(\DB::raw("s.centro+'".static::KEY_SEPARATOR."'+s.cod_bodega"), $almacenes);

        return $query;
    }
}
