<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Orm\OrmCard;
use App\OrmModel\Metrics\EvolSaldoInversiones;
use App\OrmModel\Metrics\EvolUtilInversiones;
use App\OrmModel\Metrics\GastoVisa;
use App\OrmModel\Metrics\ResumenGastos;
use App\OrmModel\Metrics\ResumenVisa;
use App\OrmModel\Metrics\SaldoCtaBci;
use App\OrmModel\Metrics\SaldoCtaSantander;
use App\OrmModel\Metrics\SaldoCtaItau;
use App\OrmModel\Metrics\SaldoGranValor;
use App\OrmModel\Metrics\SaldoInversiones;
use App\OrmModel\Metrics\SaldoPrefAhorro;
use App\OrmModel\Metrics\UtilInversiones;
use App\OrmModel\src\Metrics\Metric;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

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
     * @param  Request  $request
     * @return Metric[]
     */
    protected function cards(Request $request): array
    {
        return [
            SaldoCtaBci::make()->prefix('$'),
            SaldoCtaSantander::make()->prefix('$'),
            SaldoCtaItau::make()->prefix('$'),
            ResumenGastos::make()->prefix('$'),
            ResumenVisa::make()->prefix('$'),
            GastoVisa::make(),
            UtilInversiones::make()->prefix('$'),
            SaldoInversiones::make()->prefix('$'),
            EvolSaldoInversiones::make()->setTitle('Evol Saldo Inv'),
            EvolUtilInversiones::make()->setTitle('Evol Util Inv'),
            // SaldoGranValor::make(),
            // SaldoPrefAhorro::make(),
        ];
    }
}
