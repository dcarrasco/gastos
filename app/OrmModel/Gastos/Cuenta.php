<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\src\OrmField\BelongsTo;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class Cuenta extends Resource
{
    public string $model = \App\Models\Gastos\Cuenta::class;

    public string $icono = 'credit-card';

    public string $title = 'cuenta';

    public array $search = [
        'id', 'cuenta',
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
