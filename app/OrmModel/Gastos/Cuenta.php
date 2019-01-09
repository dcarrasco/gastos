<?php

namespace App\OrmModel\Gastos;

use Carbon\Carbon;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\Gastos\Banco;
use App\OrmModel\OrmField\Text;
use App\OrmModel\Gastos\TipoCuenta;
use App\OrmModel\OrmField\BelongsTo;
use App\Gastos\TipoCuenta as TipoCuentaModel;

class Cuenta extends Resource
{
    public $model = 'App\Gastos\Cuenta';
    public $icono = 'credit-card';
    public $title = 'cuenta';
    public $search = [
        'id', 'cuenta'
    ];

    public $orderBy = 'cuenta';

    public function fields(Request $request)
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
