<?php

namespace App\Inventario\Reporte;

use DB;
use App\Helpers\Reporte as ReporteBase;

class ReporteDetalleHoja extends Reporte
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function getDatos()
    {
        $selectFields = array_merge(
            ['d.ubicacion', 'd.catalogo', 'd.descripcion', 'd.lote', 'd.centro', 'd.almacen'],
            $this->selectFieldsCantidades()
        );
        $groupByFields = ['d.ubicacion', 'd.catalogo', 'd.descripcion', 'd.lote', 'd.centro', 'd.almacen'];

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->where('hoja', request('hoja', 0))
            ->orderBy('ubicacion')
            ->get();
    }

    public function getCampos()
    {
        $campos = ['ubicacion', 'catalogo', 'descripcion', 'lote', 'centro', 'almacen'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        ReporteBase::setOrderCampos($campos, 'ubicacion');

        return $campos;
    }
}
