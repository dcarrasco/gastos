<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Stock\ModulosConsultaStock;
use App\Stock\StockSapMovil;
use App\Stock\StockSapFija;
use App\Stock\TipoAlmacenSap;
use App\Stock\AlmacenSap;

class ConsultaStockController extends Controller
{

    use ModulosConsultaStock;

    const FECHA_ULTIMODIA = 'ultdia';
    const FECHA_TODAS     = 'todas';
    const OP_MOVIL = 'movil';
    const OP_FIJA  = 'fija';
    const SEL_TIPOSALM  = 'sel_tiposalm';
    const SEL_ALMACENES = 'sel_almacenes';

    public function consultaStockMovil()
    {
        return $this->consultaStock(static::OP_MOVIL);
    }

    public function consultaStockFija()
    {
        return $this->consultaStock(static::OP_FIJA);
    }

    public function consultaStock($tipoOp = self::OP_MOVIL)
    {
        $moduloSelected = 'consulta-stock-'.$tipoOp;

        $tipoFecha = request('sel_fechas', self::FECHA_ULTIMODIA);
        $tipoAlm   = request('sel_tiposalm', self::SEL_TIPOSALM);

        $stockSap = $tipoOp === self::OP_MOVIL ? new StockSapMovil : new StockSapFija;
        $comboFechas = $stockSap::fechasStock($tipoFecha);
        $comboAlmacenes = ($tipoAlm === self::SEL_TIPOSALM) ? TipoAlmacenSap::getComboTiposOperacion($tipoOp) : AlmacenSap::getComboTiposOperacion($tipoOp);
        $datosGrafico = false;
        $tablaStock = $stockSap::getStock();

        return view('stock_sap.ver_stock', compact('moduloSelected', 'tipoOp', 'comboFechas', 'comboAlmacenes', 'datosGrafico', 'tablaStock'));
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
