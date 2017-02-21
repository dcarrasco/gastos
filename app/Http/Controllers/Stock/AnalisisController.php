<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Stock\ReportesSeries;

class AnalisisController extends Controller
{

    use ReportesSeries;

    public function analisisSeries()
    {
        $series = request('series');

        $reporteMovimientos = request('show_mov') ? ReportesSeries::reporteMovimientos($series) : null;
        $reporteDespachos   = request('show_despachos') ? ReportesSeries::reporteDespachos($series) : null;
        $reporteStockSAP    = request('show_stock_sap') ? ReportesSeries::reporteStockSAP($series) : null;
        $reporteStockSCL    = request('show_stock_scl') ? ReportesSeries::reporteStockSCL($series) : null;

        return view('stock_sap.analisis_series_view', compact('reporteMovimientos', 'reporteDespachos', 'reporteStockSAP', 'reporteStockSCL'));
    }
}
