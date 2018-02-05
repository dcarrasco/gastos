<?php

namespace App\Stock;

use DB;
use App\Helpers\Reporte;
use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class StockSapFija extends OrmModel
{
    protected static $campos = [
        'fecha_stock' => ['titulo'=>'Fecha', 'tipo'=>'fecha'],
        'tipo' => ['titulo'=>'Tipo almacen'],
        'centro' => ['titulo'=>'Centro'],
        'almacen' => ['titulo'=>'Almacen'],
        'des_almacen' => ['titulo'=>'Desc Almacen'],
        'material' => ['titulo'=>'Material'],
        'lote' => ['titulo'=>'Lote'],
        'estado' => ['titulo'=>'Tipo Stock'],
        'tipo_material' => ['titulo'=>'Tipo Material'],
        'cant' => ['titulo'=>'Cantidad', 'tipo'=>'numero', 'class'=>'text-right'],
        'valor' => ['titulo'=>'Monto', 'tipo'=>'valor', 'class'=>'text-right'],
    ];

    protected static $selectFields = [];

    protected static $groupByFields = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = DB::raw(config('invfija.bd_stock_fija').' as s');
    }

    public static function todasFechasStock()
    {
        return DB::table(config('invfija.bd_stock_fija_fechas'))
            ->orderBy('fecha_stock', 'desc')
            ->get();
    }

    public static function ultDiaFechasStock()
    {
        return DB::table(config('invfija.bd_stock_fija_fechas'))
            ->select([
                DB::raw('100*year(fecha_stock)+month(fecha_stock) as mes'),
                DB::raw('max(fecha_stock) as fecha_stock'),
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

        if (request('material') === 'material') {
            $queryStock = $queryStock->joinMaterial();
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
            static::addSelect(['s.centro', 's.almacen', 'a.des_almacen'], true);
        }

        if (request('material') === 'material') {
            static::addSelect(['s.material', 'c.descripcion'], true);
        }

        if (request('lote') === 'lote') {
            static::addSelect('s.lote', true);
        }

        if (request('tipo_stock') === 'tipo_stock') {
            static::addSelect('s.estado', true);
        }

        static::addSelect([DB::raw('sum(cantidad) as cant'), DB::raw('sum(valor) as valor')]);

        return ['select' => static::$selectFields, 'groupBy' => static::$groupByFields];
    }

    public function scopeFiltroTipoAlmacen($query, $tiposAlmacen = [])
    {
        if (empty($tiposAlmacen)) {
            return $query;
        }

        $query = $query
            ->leftJoin(DB::raw(config('invfija.bd_almacenes_sap').' as a'), function ($join) {
                $join->on('s.centro', '=', 'a.centro');
                $join->on('s.almacen', '=', 'a.cod_almacen');
            })
            ->leftJoin(DB::raw(config('invfija.bd_tipoalmacen_sap').' as ta'), function ($join) {
                $join->on('s.centro', '=', 'ta.centro');
                $join->on('s.almacen', '=', 'ta.cod_almacen');
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
                $join->on('s.almacen', '=', 'a.cod_almacen');
            })
            ->whereIn(DB::raw("s.centro+'".static::KEY_SEPARATOR."'+s.almacen"), $almacenes);

        return $query;
    }

    public function scopeJoinMaterial($query)
    {
        return $query->leftJoin(DB::raw(config('invfija.bd_catalogos').' as c'), 's.material', '=', 'c.catalogo');
    }
}
