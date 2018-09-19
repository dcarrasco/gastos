<?php

namespace App\Stock;

use DB;
use App\Helpers\Reporte;
use App\OrmModel\Resource;
use App\OrmModel\OrmField;

class StockSapMovil extends Resource
{
    protected static $selectFields = [];

    protected static $groupByFields = [];

    protected static $tipoMaterial = "CASE "
        ."WHEN (substring(cod_articulo,1,8)='PKGCLOTK' OR substring(cod_articulo,1,2)='TS') THEN 'SIMCARD' "
        ."WHEN substring(cod_articulo, 1,2) in ('TM','TO','TC','PK','PO') THEN 'EQUIPOS' "
        ."ELSE 'OTROS' "
        ."END";

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
        'LU' => ['titulo'=>'Cant LU', 'tipo'=>'numero', 'class'=>'text-right'],
        'BQ' => ['titulo'=>'Cant BQ', 'tipo'=>'numero', 'class'=>'text-right'],
        'CC' => ['titulo'=>'Cant CC', 'tipo'=>'numero', 'class'=>'text-right'],
        'TT' => ['titulo'=>'Cant TT', 'tipo'=>'numero', 'class'=>'text-right'],
        'OT' => ['titulo'=>'Cant OT', 'tipo'=>'numero', 'class'=>'text-right'],
        'VAL_LU' => ['titulo'=>'Valor LU', 'tipo'=>'valor', 'class'=>'text-right'],
        'VAL_BQ' => ['titulo'=>'Valor BQ', 'tipo'=>'valor', 'class'=>'text-right'],
        'VAL_CC' => ['titulo'=>'Valor CC', 'tipo'=>'valor', 'class'=>'text-right'],
        'VAL_TT' => ['titulo'=>'Valor TT', 'tipo'=>'valor', 'class'=>'text-right'],
        'VAL_OT' => ['titulo'=>'Valor OT', 'tipo'=>'valor', 'class'=>'text-right'],
    ];

    // protected $dates = ['fecha_stock'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = DB::raw(config('invfija.bd_stock_movil').' as s');
    }

    public static function todasFechasStock()
    {
        return DB::table(config('invfija.bd_stock_movil_fechas'))
            ->orderBy('fecha_stock', 'desc')
            ->get();
    }

    public static function ultDiaFechasStock()
    {
        return DB::table(config('invfija.bd_stock_movil_fechas'))
            ->select([
                DB::raw('100*year(fecha_stock)+month(fecha_stock) as mes'),
                DB::raw('max(fecha_stock) as fecha_stock')
            ])
            ->groupBy([DB::raw('100*year(fecha_stock)+month(fecha_stock)')])
            ->orderBy('mes', 'desc')
            ->get();
    }

    public static function fechasStock($tipoFecha = 'ultdia')
    {
        $fechas = ($tipoFecha === 'ultdia') ? static::ultDiaFechasStock() : static::todasFechasStock();

        return $fechas
        ->mapWithKeys(function ($item) {
            return [fmtFecha_db($item->fecha_stock) => fmtFecha($item->fecha_stock)];
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

    protected static function addSelect($fields, $addGroup = false)
    {
        if (!is_array($fields)) {
            $fields = [$fields];
        }

        foreach ($fields as $field) {
            static::$selectFields[] = $field;

            if ($addGroup) {
                static::$groupByFields[] = $field;
            }
        }
    }

    protected static function getSelectFields()
    {
        static::addSelect('s.fecha_stock', true);

        if (request('sel_tiposalm') === 'sel_tiposalm') {
            static::addSelect('t.tipo', true);
        }

        if (request('almacen') === 'almacen' or request('sel_tiposalm') === 'sel_almacenes') {
            static::addSelect(['s.centro', 's.cod_bodega', 'a.des_almacen'], true);
        }

        if (request('material') === 'material') {
            static::addSelect('s.cod_articulo', true);
        }

        if (request('lote') === 'lote') {
            static::addSelect('s.lote', true);
        }

        static::$selectFields[]  = DB::raw(static::$tipoMaterial.' as tipo_material');
        static::$groupByFields[] = DB::raw(static::$tipoMaterial);

        if (request('tipo_stock') === 'tipo_stock') {
            static::addSelect([
                DB::raw('sum(s.libre_utilizacion) as LU'),
                DB::raw('sum(s.bloqueado) as BQ'),
                DB::raw('sum(s.contro_calidad) as CC'),
                DB::raw('sum(s.transito_traslado) as TT'),
                DB::raw('sum(s.otros) as OT'),
                DB::raw('sum(s.VAL_LU) as VAL_LU'),
                DB::raw('sum(s.VAL_BQ) as VAL_BQ'),
                DB::raw('sum(s.VAL_CQ) as VAL_CC'),
                DB::raw('sum(s.VAL_TT) as VAL_TT'),
                DB::raw('sum(s.VAL_OT) as VAL_OT'),
            ]);
        } else {
            static::addSelect([DB::raw(static::$sumCantidad.' as cant'), DB::raw(static::$sumValor.' as valor')]);
        }

        return ['select'=>static::$selectFields, 'groupBy'=>static::$groupByFields];
    }

    public function scopeFiltroTipoAlmacen($query, $tiposAlmacen = [])
    {
        if (empty($tiposAlmacen)) {
            return $query;
        }

        $query = $query
            ->leftJoin(DB::raw(config('invfija.bd_almacenes_sap').' as a'), function ($join) {
                $join->on('s.centro', '=', 'a.centro');
                $join->on('s.cod_bodega', '=', 'a.cod_almacen');
            })
            ->leftJoin(DB::raw(config('invfija.bd_tipoalmacen_sap').' as ta'), function ($join) {
                $join->on('s.centro', '=', 'ta.centro');
                $join->on('s.cod_bodega', '=', 'ta.cod_almacen');
            })
            ->leftJoin(DB::raw(config('invfija.bd_tiposalm_sap').' as t'), 't.id_tipo', '=', 'ta.id_tipo')
            ->whereIn('t.id_tipo', $tiposAlmacen);

        return $query;
    }

    public function scopeFiltroAlmacen($query, $almacenes = [])
    {
        if (empty($almacenes)) {
            return $query;
        }

        $query = $query
            ->leftJoin(DB::raw(config('invfija.bd_almacenes_sap').' as a'), function ($join) {
                $join->on('s.centro', '=', 'a.centro');
                $join->on('s.cod_bodega', '=', 'a.cod_almacen');
            })
            ->whereIn(DB::raw("s.centro+'".static::KEY_SEPARATOR."'+s.cod_bodega"), $almacenes);

        return $query;
    }
}
