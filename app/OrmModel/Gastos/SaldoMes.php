<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\src\OrmField\BelongsTo;
use App\OrmModel\src\OrmField\Currency;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Number;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class SaldoMes extends Resource
{
    public string $model = \App\Models\Gastos\SaldoMes::class;

    public string $label = 'Saldo Mes';

    public string $labelPlural = 'Saldos Mes';

    public string $icono = 'calculator';

    public string $title = 'id';

    public array $search = [
        'id', 'anno', 'mes',
    ];

    public $orderBy = [
        'cuenta_id' => 'asc',
        'anno' => 'asc',
        'mes' => 'asc',
    ];

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Cuenta', 'cuenta', Cuenta::class)
                ->rules('required'),

            Number::make('Año', 'anno')
                ->sortable()
                ->rules('required'),

            Number::make('Mes')
                ->sortable()
                ->rules('required'),

            Currency::make('Saldo inicial')
                ->sortable()
                ->rules('required'),

            Currency::make('Saldo final')
                ->sortable()
                ->rules('required'),
        ];
    }
}
