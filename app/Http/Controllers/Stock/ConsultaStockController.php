<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Stock\StockSapMovil;
use App\Stock\TipoAlmacenSap;
use App\Stock\AlmacenSap;

class ConsultaStockController extends Controller
{
    const FECHA_ULTIMODIA = 'ultdia';
    const FECHA_TODAS     = 'todas';
    const OP_MOVIL = 'movil';
    const OP_FIJA  = 'fija';
    const SEL_TIPOSALM  = 'sel_tiposalm';
    const SEL_ALMACENES = 'sel_almacenes';

    public function consultaStock()
    {
        $tipoFecha = request()->input('sel_fechas', self::FECHA_ULTIMODIA);
        $tipoOp    = request()->input('tipo_op', self::OP_MOVIL);
        $tipoAlm   = request()->input('sel_tiposalm', self::SEL_TIPOSALM);

        $comboFechas = StockSapMovil::fechasStock($tipoFecha);
        $comboAlmacenes = TipoAlmacenSap::getComboTiposOperacion($tipoOp);
        $datosGrafico = false;
        dump(request()->input(), StockSapMovil::where('fecha_stock', '20170214')->first(), $stock = StockSapMovil::getStock());

        return view('stock_sap.ver_stock', compact('tipoOp', 'comboFechas', 'comboAlmacenes', 'datosGrafico'));
    }

    public function ajaxFecha($tipoOp = self::OP_MOVIL, $tipoFecha = self::FECHA_ULTIMODIA)
    {
        $stockSAP = ($tipoOp === self::OP_MOVIL) ? new StockSapMovil : '';

        return ajax_options($stockSAP::fechasStock($tipoFecha));
    }

    public function ajaxAlmacenes($tipoOp = self::OP_MOVIL, $tipoAlm = self::SEL_TIPOSALM)
    {
        $almacenes = ($tipoAlm === self::SEL_TIPOSALM) ? new TipoAlmacenSap : new AlmacenSap;

        return ajax_options($almacenes::getComboTiposOperacion($tipoOp));
    }
}
