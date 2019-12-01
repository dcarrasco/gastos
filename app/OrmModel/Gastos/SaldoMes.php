<?php

namespace App\OrmModel\Gastos;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\Gastos\Cuenta;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Number;
use App\OrmModel\src\OrmField\Currency;
use App\OrmModel\src\OrmField\BelongsTo;

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
