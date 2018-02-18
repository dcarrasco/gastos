<?php

namespace App\Inventario\Reporte;

use DB;
use App\Helpers\Reporte as ReporteBase;

class ReporteMaterial extends Reporte
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function getDatos()
    {
        $reporteFields = ['d.catalogo', 'd.descripcion', 'd.um', 'c.pmp'];

        $selectFields = array_merge($reporteFields, $this->selectFieldsCantidades());
        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->orderBy('catalogo')
            ->get();
    }

    public function getCampos()
    {
        $campos = ['catalogo', 'descripcion', 'um', 'pmp'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        ReporteBase::setOrderCampos($campos, 'catalogo');

        return $campos;
    }
}
