<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrmModel\Metrics\GastoVisa;
use Illuminate\Contracts\View\View;
use App\OrmModel\src\Metrics\Metric;
use App\Http\Controllers\Orm\OrmCard;
use App\OrmModel\Metrics\ResumenVisa;
use App\OrmModel\Metrics\SaldoCtaCte;
use App\OrmModel\Metrics\ResumenGastos;
use App\OrmModel\Metrics\SaldoGranValor;
use App\OrmModel\Metrics\SaldoPrefAhorro;
use App\OrmModel\Metrics\UtilInversiones;
use App\OrmModel\Metrics\SaldoInversiones;
use App\OrmModel\Metrics\EvolUtilInversiones;

class HomeController extends Controller
{
    use OrmCard;


    public function index(Request $request): View
    {
        return view('gastos.home', [
            'cards' => collect($this->cards($request))->map->render($request),
        ]);
    }

    /**
     * Devuelve metricas a ser desplegadas
     *
     * @param Request $request
     * @return Metric[]
     */
    protected function cards(Request $request): array
    {
        return [
            SaldoCtaCte::make()->prefix('$'),
            ResumenGastos::make()->prefix('$'),
            ResumenVisa::make()->prefix('$'),
            GastoVisa::make(),
            SaldoInversiones::make(),
            UtilInversiones::make()->prefix('$'),
            EvolUtilInversiones::make(),
            SaldoGranValor::make(),
            SaldoPrefAhorro::make(),
        ];
    }
}
