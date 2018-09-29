<?php

namespace App\OrmModel\Gastos;

use Carbon\Carbon;
use App\Gastos\TipoGasto as TipoGastoModel;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\Acl\Usuario;
use App\OrmModel\OrmField\Id;
use App\Gastos\GlosaTipoGasto;
use App\OrmModel\Gastos\Cuenta;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\Gastos\TipoGasto;
use App\OrmModel\OrmField\BelongsTo;
use App\OrmModel\Gastos\TipoMovimiento;

class Gasto extends Resource
{
    public $model = 'App\Gastos\Gasto';
    public $icono = 'dollar';
    public $title = 'id';
    public $search = [
        'id', 'monto', 'glosa', 'serie'
    ];

    public $orderBy = 'id';

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Cuenta', 'cuenta', Cuenta::class)
                ->rules('required'),

            Number::make('Año', 'anno')->sortable()->rules('required'),

            Number::make('Mes')->sortable()->rules('required'),

            Text::make('Fecha')->sortable(),

            Text::make('Glosa')->sortable()->hideFromIndex(),

            Text::make('Serie')->sortable(),

            BelongsTo::make('Tipo de Gasto', 'tipoGasto', TipoGasto::class)
                ->rules('required'),

            BelongsTo::make('Tipo de Movimiento', 'tipoMovimiento', TipoMovimiento::class)
                ->rules('required'),

            Number::make('Monto')->sortable()->rules('required'),

            BelongsTo::make('Usuario', 'usuario', Usuario::class)
                ->hideFromIndex()
                ->rules('required'),

            // Text::make('Fecha')->sortable()->rules('max:20')->hideFromIndex(),

            // Text::make('Glosa')->sortable()->rules('max:200')->hideFromIndex(),

            // Text::make('Serie')->sortable()->rules('max:50')->hideFromIndex(),
        ];
    }

}
