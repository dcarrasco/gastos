<?php

namespace App\Inventario\Reporte;

use DB;
use App\Helpers\Reporte as ReporteBase;

class ReporteHoja extends Reporte
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function getDatos()
    {
        $selectFields = array_merge(
            ['d.hoja', DB::raw('a.nombre as auditor'), DB::raw('u.nombre as digitador')],
            $this->selectFieldsCantidades()
        );
        $groupByFields = ['d.hoja', 'a.nombre', 'u.nombre'];

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->leftJoin(DB::raw(config('invfija.bd_auditores').' as a'), 'd.auditor', '=', 'a.id')
            ->leftJoin(DB::raw(config('invfija.bd_usuarios').' as u'), 'd.digitador', '=', 'u.id')
            ->orderBy('hoja')
            ->get();
    }

    public function getCampos()
    {
        $campos = ['hoja', 'auditor', 'digitador'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        ReporteBase::setOrderCampos($campos, 'hoja');

        return $campos;
    }


}
