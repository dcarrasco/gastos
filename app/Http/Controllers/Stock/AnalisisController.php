<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;
use App\Stock\ReportesSeries;

class AnalisisController extends Controller
{

    use ReportesSeries;

    public function analisisSeries()
    {
        $reporteMovimientos = ReportesSeries::reporteMovimientos(request()->input('series'));

        return view('stock_sap.analisis_series_view', compact('reporteMovimientos'));
    }
}
