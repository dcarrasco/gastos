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
        return DetalleInventario::where('id_inventario', '=', $this->id)
            ->get();
    }
}
