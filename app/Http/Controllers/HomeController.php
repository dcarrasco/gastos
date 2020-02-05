<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\OrmModel\Metrics\ResumenVisa;
use App\OrmModel\Metrics\ResumenGastos;
use App\OrmModel\Metrics\SaldoGranValor;

class HomeController extends Controller
{
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
        return view('home', [
            'cards' => collect($this->cards($request))->map->render($request),
        ]);
    }

    protected function cards(Request $request)
    {
        return [
            (new ResumenGastos())->prefix('$'),
            (new ResumenVisa())->prefix('$'),
            new SaldoGranValor(),
        ];
    }
}
