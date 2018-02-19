<?php

namespace App\Inventario\Reporte;

use DB;
use App\Inventario\Inventario;
use App\Helpers\Reporte as ReporteBase;

class Reporte
{
    protected $id;

    protected $camposReporte = [
        'hoja' => [
            'titulo' => 'Hoja',
            'tipo' => 'link_registro',
            'route' => 'inventario.reporte',
            'routeFixedParams' => ['tipo' => 'detalleHoja'],
            'routeVariableParams' => ['hoja' => 'hoja']
        ],
        'auditor' => ['titulo' => 'Auditor'],
        'digitador' => ['titulo' => 'Digitador'],

        'catalogo' => [
            'titulo' => 'Catalogo',
            'tipo' => 'link_registro',
            'route' => 'inventario.reporte',
            'routeFixedParams' => ['detalleMaterial'],
            'routeVariableParams' => ['catalogo' => 'catalogo']
        ],
        'descripcion' => ['titulo' => 'Descripcion'],
        'um' => ['titulo' => 'UM'],
        'pmp' => ['titulo' => 'PMP', 'class' => 'text-center', 'tipo' => 'valor_pmp'],

        'ubicacion' => ['titulo' => 'Ubicacion'],
        'tipo_ubicacion' => ['titulo' => 'Tipo de Ubicacion'],

        'lote' => ['titulo' => 'Lote'],
        'centro' => ['titulo' => 'Centro'],
        'almacen' => ['titulo' => 'Almacen'],
        'tipo_ajuste' => ['titulo' => 'Tipo Dif'],
        'glosa_ajuste' => ['titulo' => 'Observacion'],

        'sum_stock_sap' => ['titulo' => 'Cant SAP', 'class' => 'text-center', 'tipo' => 'numero'],
        'sum_stock_fisico' => ['titulo' => 'Cant Fisico', 'class' => 'text-center', 'tipo' => 'numero'],
        'sum_stock_ajuste' => ['titulo' => 'Cant Ajuste', 'class' => 'text-center', 'tipo' => 'numero'],
        'sum_stock_diff' => ['titulo' => 'Cant Dif', 'class' => 'text-center', 'tipo' => 'numero_dif'],

        'sum_valor_sap' => ['titulo' => 'Valor SAP', 'class' => 'text-center', 'tipo' => 'valor'],
        'sum_valor_fisico' => ['titulo' => 'Valor Fisico', 'class' => 'text-center', 'tipo' => 'valor'],
        'sum_valor_ajuste' => ['titulo' => 'Valor Ajuste', 'class' => 'text-center', 'tipo' => 'valor'],
        'sum_valor_diff' => ['titulo' => 'Valor Dif', 'class' => 'text-center', 'tipo' => 'valor_dif'],

        'q_faltante' => ['titulo'=>'Cant Faltante', 'class'=>'text-center', 'tipo'=>'numero'],
        'q_sobrante' => ['titulo'=>'Cant Sobrante', 'class'=>'text-center', 'tipo'=>'numero'],
        'q_coincidente' => ['titulo'=>'Cant Coincidente', 'class'=>'text-center', 'tipo'=>'numero'],
        'v_faltante' => ['titulo'=>'Valor Faltante', 'class'=>'text-center', 'tipo'=>'valor'],
        'v_sobrante' => ['titulo'=>'Valor Sobrante', 'class'=>'text-center', 'tipo'=>'valor'],
        'v_coincidente' => ['titulo'=>'Valor Coincidente', 'class'=>'text-center', 'tipo'=>'valor'],
    ];

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    public function reporte()
    {
        return (new ReporteBase($this->getDatos(), $this->getCampos()))->make();
    }

    protected function selectFieldsCantidades()
    {
        $inclAjuste = request('incl_ajustes') ? ' + d.stock_ajuste' : '';

        return [
            DB::raw('sum(d.stock_sap) as sum_stock_sap'),
            DB::raw('sum(d.stock_fisico) as sum_stock_fisico'),
            DB::raw('sum(d.stock_ajuste) as sum_stock_ajuste'),
            DB::raw("sum(d.stock_fisico-d.stock_sap{$inclAjuste}) as sum_stock_diff"),
            DB::raw('sum(d.stock_sap*c.pmp) as sum_valor_sap'),
            DB::raw('sum(d.stock_fisico*c.pmp) as sum_valor_fisico'),
            DB::raw('sum(d.stock_ajuste*c.pmp) as sum_valor_ajuste'),
            DB::raw("sum((d.stock_fisico-d.stock_sap{$inclAjuste})*c.pmp) as sum_valor_diff"),
        ];
    }

    protected function camposCantidades()
    {
        $campos = [
            10 => 'sum_stock_sap',
            20 => 'sum_stock_fisico',
            30 => 'sum_stock_diff',
            40 => 'sum_valor_sap',
            50 => 'sum_valor_fisico',
            60 => 'sum_valor_diff',
        ];

        if (request('incl_ajustes')) {
            $campos[25] = 'sum_stock_ajuste';
        }
        if (request('incl_ajustes')) {
            $campos[55] = 'sum_valor_ajuste';
        }

        ksort($campos);

        return $this->camposreporte($campos);
    }

    protected function queryBaseReporteInventario($selectFields = [], $groupByFields = [])
    {
        return DB::table(DB::raw(config('invfija.bd_detalle_inventario').' as d'))
            ->where('id_inventario', $this->id)
            ->leftJoin(
                DB::raw(config('invfija.bd_catalogos').' as c'),
                DB::raw('d.catalogo '.env('BD_COLLATE', '')),
                'c.catalogo'
            )
            ->select($selectFields)
            ->groupBy($groupByFields);
    }

    protected function camposReporte($campos = [])
    {
        return collect($this->camposReporte)->only($campos)->all();
    }
}
