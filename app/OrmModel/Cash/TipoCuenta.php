<?php

namespace App\OrmModel\Cash;

use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class TipoCuenta extends Resource
{
    public string $model = \App\Models\Cash\TipoCuenta::class;

    public string $icono = 'credit-card';

    public string $title = 'nombre';

    public array $search = [
        'tipo', 'nombre',
    ];

    public $orderBy = 'tipo';

    public function fields(Request $request): array
    {
        return [
            Text::make('Tipo Cuenta')->sortable()->rules('max:250', 'required', 'unique'),
            Text::make('Nombre')->sortable()->rules('max:250', 'required', 'unique'),
            Text::make('Tipo')->sortable()->rules('max:250', 'required'),
        ];
    }
}
