<?php

namespace App\Inventario\Reporte;

use DB;
use App\Helpers\Reporte as ReporteBase;

class ReporteMaterialFaltante extends Reporte
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function getDatos()
    {
        $inclAjuste = request('incl_ajustes') ? ' + d.stock_ajuste' : '';

        $reporteFields = ['d.catalogo', 'd.descripcion', 'd.um', 'c.pmp'];

        $maxStockSapFisico = "0.5*("
            ."SUM(stock_sap+stock_fisico{$inclAjuste}) - ABS(SUM(stock_sap-(stock_fisico{$inclAjuste})))"
            .")";

        $selectFields = array_merge($reporteFields, [
            DB::raw("(SUM(stock_sap) - {$maxStockSapFisico}) as q_faltante"),
            DB::raw("({$maxStockSapFisico}) as q_coincidente"),
            DB::raw("(SUM(stock_fisico{$inclAjuste}) - {$maxStockSapFisico}) as q_sobrante"),
            DB::raw("c.pmp*(SUM(stock_sap) - {$maxStockSapFisico}) as v_faltante"),
            DB::raw("c.pmp*({$maxStockSapFisico}) as v_coincidente"),
            DB::raw("c.pmp*(SUM(stock_fisico{$inclAjuste}) - {$maxStockSapFisico}) as v_sobrante"),
        ]);

        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->orderBy('catalogo')
            ->get();
    }

    public function getCampos()
    {
        $campos = $this->camposReporte([
            'catalogo', 'descripcion', 'um', 'pmp',
            'q_faltante', 'q_coincidente', 'q_sobrante',
            'v_faltante', 'v_coincidente', 'v_sobrante'
        ]);
        ReporteBase::setOrderCampos($campos, 'catalogo');

        return $campos;
    }
}
