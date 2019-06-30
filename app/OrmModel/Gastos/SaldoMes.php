<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\Gastos\Cuenta;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\Currency;
use App\OrmModel\OrmField\BelongsTo;

class SaldoMes extends Resource
{
    public $model = 'App\Gastos\SaldoMes';
    public $label = 'Saldo Mes';
    public $labelPlural = 'Saldos Mes';
    public $icono = 'calculator';
    public $title = 'id';
    public $search = [
        'id', 'anno', 'mes'
    ];

    public $orderBy = [
        'cuenta_id' => 'asc',
        'anno' => 'asc',
        'mes' => 'asc',
    ];

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Cuenta', 'cuenta', Cuenta::class)
                ->rules('required'),

            Number::make('Año', 'anno')->sortable()->rules('required'),

            Number::make('Mes')->sortable()->rules('required'),

            Currency::make('Saldo inicial')->sortable()->rules('required'),

            Currency::make('Saldo final')->sortable()->rules('required'),

        ];
    }
}
