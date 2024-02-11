<?php

namespace App\OrmModel\Cash;

use App\OrmModel\src\OrmField\BelongsTo;
use App\OrmModel\src\OrmField\Currency;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\Boolean;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class Cuenta extends Resource
{
    public string $model = \App\Models\Cash\Cuenta::class;

    public string $icono = 'credit-card';

    public string $title = 'nombre';

    public array $search = [
        'id', 'nombre',
    ];

    public $orderBy = 'codigo';

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            Text::make('Codigo')->sortable()->rules('max:250'),
            Text::make('Nombre')->sortable()->rules('max:250', 'required', 'unique'),
            Text::make('Descripcion')->sortable()->rules('max:250'),

            BelongsTo::make('Tipo Cuenta', 'tipoCuenta', TipoCuenta::class)
                ->rules('required'),

            Text::make('Moneda')->sortable()->rules('max:250', 'required')->hideFromIndex(),
            Text::make('Color')->sortable()->rules('max:250')->hideFromIndex(),

            Currency::make('Limite superior')->hideFromIndex(),
            Currency::make('Limite inferior')->hideFromIndex(),

            Boolean::make('Contenedor')->rules('required'),
            Boolean::make('Oculto')->rules('required'),

            BelongsTo::make('Cuenta superior', 'cuentaSuperior', Cuenta::class)
                ->rules('required')->hideFromIndex(),
        ];
    }
}
