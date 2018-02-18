<?php

namespace App\Inventario\Reporte;

use DB;
use App\Helpers\Reporte as ReporteBase;

class ReporteDetalleMaterial extends Reporte
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function getDatos()
    {
        $reporteFields = ['d.catalogo', 'd.descripcion', 'd.ubicacion', 'd.hoja', 'd.lote'];

        $selectFields = array_merge($reporteFields, $this->selectFieldsCantidades());
        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->where('d.catalogo', request('catalogo'))
            ->orderBy('ubicacion')
            ->get();
    }

    public function getCampos()
    {
        $campos = ['catalogo', 'descripcion', 'ubicacion', 'hoja', 'lote'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        ReporteBase::setOrderCampos($campos, 'ubicacion');

        return $campos;
    }
}
