<?php

namespace App\Inventario;

use App\Helpers\Reporte;

trait AjustesInventario
{
    public function detalleAjustes()
    {
        $inclAjuste = request()->input('incl_ajustes') ? '+stock_ajuste' : '';

        return DetalleInventario::where('id_inventario', $this->id)
            ->where(\DB::raw('stock_fisico-stock_sap'.$inclAjuste), '<>', 0)
            ->orderBy('catalogo')
            ->orderBy('lote')
            ->orderBy('centro')
            ->orderBy('almacen')
            ->orderBy('ubicacion')
            ->paginate();
    }
}
