<?php

namespace App\Inventario\Reporte;

use DB;
use App\Helpers\Reporte as ReporteBase;

class ReporteUbicacion extends Reporte
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function getDatos()
    {
        $reporteFields = ['d.ubicacion'];

        $selectFields = array_merge($reporteFields, $this->selectFieldsCantidades());
        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->orderBy('d.ubicacion')
            ->get();
    }

    public function getCampos()
    {
        $campos = ['ubicacion'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        ReporteBase::setOrderCampos($campos, 'catalogo');

        return $campos;
    }
}
