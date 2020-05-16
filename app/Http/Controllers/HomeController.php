<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrmModel\Metrics\GastoVisa;
use App\Http\Controllers\Orm\OrmCard;
use App\OrmModel\Metrics\ResumenVisa;
use App\OrmModel\Metrics\SaldoCtaCte;
use App\OrmModel\Metrics\ResumenGastos;
use App\OrmModel\Metrics\SaldoGranValor;
use App\OrmModel\Metrics\SaldoPrefAhorro;
use App\OrmModel\Metrics\SaldoTotalInversiones;

class HomeController extends Controller
{
    use OrmCard;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('gastos.home', [
            'cards' => collect($this->cards($request))->map->render($request),
        ]);
    }

    protected function cards(Request $request)
    {
        return [
            (new SaldoCtaCte())->prefix('$'),
            (new ResumenGastos())->prefix('$'),
            (new ResumenVisa())->prefix('$'),
            new GastoVisa(),
            new SaldoGranValor(),
            new SaldoPrefAhorro(),
            new SaldoTotalInversiones(),
        ];
    }
}
