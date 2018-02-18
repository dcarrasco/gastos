<?php

namespace App\Inventario\Reporte;

use DB;
use App\Helpers\Reporte as ReporteBase;

class ReporteTiposUbicacion extends Reporte
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function getDatos()
    {
        $reporteFields = ['t.tipo_ubicacion', 'd.ubicacion'];

        $selectFields = array_merge($reporteFields, $this->selectFieldsCantidades());
        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->leftJoin(DB::raw(config('invfija.bd_inventarios').' as i'), 'd.id_inventario', '=', 'i.id')
            ->leftJoin(DB::raw(config('invfija.bd_ubic_tipoubic').' as ut'), function ($join) {
                $join->on('d.ubicacion', '=', 'ut.ubicacion');
                $join->on('i.tipo_inventario', '=', 'ut.tipo_inventario');
            })
            ->leftJoin(DB::raw(config('invfija.bd_tipo_ubicacion').' as t'), 'ut.id_tipo_ubicacion', '=', 't.id')
            ->orderBy('t.tipo_ubicacion')
            ->get();
    }

    public function getCampos()
    {
        $campos = ['tipo_ubicacion', 'ubicacion'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        ReporteBase::setOrderCampos($campos, 'tipo_ubicacion');

        return $campos;
    }
}
