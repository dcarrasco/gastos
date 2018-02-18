<?php

namespace App\Inventario\Reporte;

use DB;
use App\Inventario\Inventario;
use App\Helpers\Reporte as ReporteBase;

class Reporte
{

    protected $id;
    protected $inventario;

    public function __construct($id = null)
    {
        $this->id = $id;
        $this->inventario = Inventario::findOrNew($id);
    }

    public function reporte()
    {
        $reporte = new ReporteBase($this->getDatos(), $this->getCampos());

        return $reporte->make();
    }

    protected function getCampo($campo)
    {
        $campos = [
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

        return array_get($campos, $campo, []);
    }

    protected function selectFieldsCantidades()
    {
        $inclAjuste = request('incl_ajustes') ? ' + d.stock_ajuste' : '';

        return [
            DB::raw('sum(d.stock_sap) as sum_stock_sap'),
            DB::raw('sum(d.stock_fisico) as sum_stock_fisico'),
            DB::raw('sum(d.stock_ajuste) as sum_stock_ajuste'),
            DB::raw('sum(d.stock_fisico-d.stock_sap'.$inclAjuste.') as sum_stock_diff'),
            DB::raw('sum(d.stock_sap*c.pmp) as sum_valor_sap'),
            DB::raw('sum(d.stock_fisico*c.pmp) as sum_valor_fisico'),
            DB::raw('sum(d.stock_ajuste*c.pmp) as sum_valor_ajuste'),
            DB::raw('sum((d.stock_fisico-d.stock_sap'.$inclAjuste.')*c.pmp) as sum_valor_diff'),
        ];
    }

    protected function camposCantidades()
    {
        $camposCantidades = [];

        $camposCantidades['sum_stock_sap'] = $this->getCampo('sum_stock_sap');
        $camposCantidades['sum_stock_fisico'] = $this->getCampo('sum_stock_fisico');
        if (request('incl_ajustes')) {
            $camposCantidades['sum_stock_ajuste'] = $this->getCampo('sum_stock_ajuste');
        }
        $camposCantidades['sum_stock_diff'] = $this->getCampo('sum_stock_diff');

        $camposCantidades['sum_valor_sap'] = $this->getCampo('sum_valor_sap');
        $camposCantidades['sum_valor_fisico'] = $this->getCampo('sum_valor_fisico');
        if (request('incl_ajustes')) {
            $camposCantidades['sum_valor_ajuste'] = $this->getCampo('sum_valor_ajuste');
        }
        $camposCantidades['sum_valor_diff'] = $this->getCampo('sum_valor_diff');

        return $camposCantidades;
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
        $camposReporte = [];
        foreach ($campos as $campo) {
            $camposReporte[$campo] = $this->getCampo($campo);
        }

        return $camposReporte;
    }
}
