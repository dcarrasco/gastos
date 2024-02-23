<?php

namespace App\OrmModel\Cash;

use App\OrmModel\src\OrmField\BelongsTo;
use App\OrmModel\src\OrmField\Currency;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\Date;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class Movimiento extends Resource
{
    public string $model = \App\Models\Cash\Movimiento::class;

    public string $icono = 'credit-card';

    public string $title = 'nombre';

    public array $search = [
        'id', 'nombre',
    ];

    public $orderBy = 'id';

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            Text::make('movimiento_id')->rules('max:250', 'required'),

            BelongsTo::make('Cuenta', 'Cuenta', Cuenta::class)->rules('required'),

            Date::make('Fecha')->sortable()->rules('required'),
            Text::make('Numero')->sortable()->rules('max:250'),
            Text::make('Descripcion')->sortable()->rules('max:250', 'required'),

            BelongsTo::make('Contracuenta', 'contracuenta', Cuenta::class)->rules('required')->hideFromIndex(),

            Text::make('Conciliado')->sortable()->rules('max:250', 'required')->hideFromIndex(),
            Text::make('Tipo cargo')->sortable()->rules('max:250'),

            Currency::make('Monto'),
            Currency::make('Balance'),
        ];
    }
}
