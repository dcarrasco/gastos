<?php

namespace App\Http\Controllers\Stock;

use App\Http\Controllers\Controller;

class AnalisisController extends Controller
{
    public function analisisSeries()
    {
        return view('stock_sap.analisis_series_view');
    }
}
