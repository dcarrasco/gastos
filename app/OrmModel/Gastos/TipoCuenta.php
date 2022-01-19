<?php

namespace App\OrmModel\Gastos;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\Select;
use App\Models\Gastos\TipoCuenta as ModelTipoCuenta;

class TipoCuenta extends Resource
{
    public string $model = \App\Models\Gastos\TipoCuenta::class;
    public string $label = 'Tipo de Cuenta';
    public string $labelPlural = 'Tipos de Cuenta';
    public string $icono = 'sitemap';
    public string $title = 'tipo_cuenta';
    public array $search = [
        'id', 'tipo_cuenta'
    ];

    public $orderBy = 'tipo_cuenta';

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            Text::make('Tipo cuenta')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Select::make('Tipo')
                ->options([
                    ModelTipoCuenta::CUENTA_GASTO => 'Gasto',
                    ModelTipoCuenta::CUENTA_INVERSION => 'Inversion',
                ])
                ->sortable()
                ->rules('required')
        ];
    }
}
