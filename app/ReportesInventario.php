<?php

namespace App;

trait ReportesInventario
{
    public function getDetalleHoja($hoja = null)
    {
        if (empty($hoja)) {
            return null;
        }

        return DetalleInventario::where('id_inventario', '=', $this->id)
            ->where('hoja', '=', $hoja)->get();
    }

    public function reporte($tipo)
    {
        $inclAjuste = request()->input('incl_ajustes') ? ' + d.stock_ajuste' : '';

        $selectFields = [
            'd.hoja',
            \DB::raw('a.nombre as auditor'),
            \DB::raw('u.nombre as digitador'),
            \DB::raw('sum(d.stock_sap) as sum_stock_sap'),
            \DB::raw('sum(d.stock_fisico) as sum_stock_fisico'),
            \DB::raw('sum(d.stock_ajuste) as sum_stock_ajuste'),
            \DB::raw('sum(d.stock_fisico-d.stock_sap'.$inclAjuste.') as sum_stock_diff'),
            \DB::raw('sum(d.stock_sap*c.pmp) as sum_valor_sap'),
            \DB::raw('sum(d.stock_fisico*c.pmp) as sum_valor_fisico'),
            \DB::raw('sum(d.stock_ajuste*c.pmp) as sum_valor_ajuste'),
            \DB::raw('sum((d.stock_fisico-d.stock_sap'.$inclAjuste.')*c.pmp) as sum_valor_diff'),
        ];

        $groupByFields = ['hoja', 'a.nombre', 'u.nombre'];

        return \DB::table(\DB::raw(config('invfija.bd_detalle_inventario').' as d'))
            ->where('id_inventario', '=', $this->id)
            ->leftJoin(\DB::raw(config('invfija.bd_auditores').' as a'), 'd.auditor', '=', 'a.id')
            ->leftJoin(\DB::raw(config('invfija.bd_usuarios').' as u'), 'd.digitador', '=', 'u.id')
            ->leftJoin(\DB::raw(config('invfija.bd_catalogos').' as c'), 'd.catalogo', '=', 'c.catalogo')
            ->select($selectFields)
            ->groupBy($groupByFields)
            ->orderBy('hoja')
            ->get();
    }
}
