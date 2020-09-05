<?php

namespace App\OrmModel\Gastos;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Banco;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\Gastos\TipoCuenta;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\BelongsTo;
use App\Models\Gastos\TipoCuenta as TipoCuentaModel;

class Cuenta extends Resource
{
    public $model = 'App\Models\Gastos\Cuenta';
    public $icono = 'credit-card';
    public $title = 'cuenta';
    public $search = [
        'id', 'cuenta'
    ];

    public $orderBy = 'cuenta';

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Banco', 'banco', Banco::class)
                ->rules('required'),

            BelongsTo::make('Tipo Cuenta', 'tipoCuenta', TipoCuenta::class)
                ->rules('required'),

            Text::make('Cuenta')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
