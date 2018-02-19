<?php

namespace App\Inventario\Reporte;

use DB;
use App\Helpers\Reporte as ReporteBase;

class ReporteAjustes extends Reporte
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function getDatos()
    {
        $reporteFields = [
            'd.catalogo',
            'd.descripcion',
            'd.lote',
            'd.centro',
            'd.almacen',
            'd.ubicacion',
            'd.hoja',
            'd.um',
            'd.glosa_ajuste'
        ];

        $queryTipoAjuste = "CASE "
            ."WHEN (stock_fisico-stock_sap+stock_ajuste) > 0 THEN 'SOBRANTE' "
            ."WHEN (stock_fisico-stock_sap+stock_ajuste) < 0 THEN 'FALTANTE' "
            ."ELSE 'OK' "
            ."END";

        $selectFields = array_merge(
            array_merge($reporteFields, [DB::raw($queryTipoAjuste.' as tipo_ajuste')]),
            $this->selectFieldsCantidades()
        );
        $groupByFields = array_merge($reporteFields, [DB::raw($queryTipoAjuste)]);

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->orderBy('d.catalogo')
            ->get();
    }

    public function getCampos()
    {
        $campos = ['catalogo', 'descripcion', 'lote', 'centro', 'almacen', 'ubicacion', 'hoja', 'um'];

        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        $campos['tipo_ajuste'] = array_get($this->camposReporte, 'tipo_ajuste');
        $campos['glosa_ajuste'] = array_get($this->camposReporte, 'glosa_ajuste');

        unset($campos['sum_valor_sap']);
        unset($campos['sum_valor_fisico']);
        unset($campos['sum_valor_diff']);

        ReporteBase::setOrderCampos($campos, 'catalogo');

        return $campos;
    }
}
