<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Acl\Usuario;
use App\OrmModel\Filters\CuentasGastos;
use App\OrmModel\Metrics\GastosPerDay;
use App\OrmModel\Metrics\MontoRegistros;
use App\OrmModel\Metrics\RegistrosPorDia;
use App\OrmModel\src\OrmField\BelongsTo;
use App\OrmModel\src\OrmField\Currency;
use App\OrmModel\src\OrmField\Date;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Number;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class Gasto extends Resource
{
    public string $model = \App\Models\Gastos\Gasto::class;

    public string $icono = 'dollar';

    public string $title = 'id';

    public array $search = [
        'id', 'monto', 'glosa', 'serie', 'anno', 'mes',
    ];

    public $orderBy = 'id';

    protected $paginationLinksDetail = true;

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Cuenta', 'cuenta', Cuenta::class)
                ->rules('required'),

            Number::make('Año', 'anno')->sortable()->rules('required')->hideFromIndex(),

            Number::make('Mes')->sortable()->rules('required')->hideFromIndex(),

            Date::make('Fecha')->sortable(),

            Text::make('Glosa')->sortable()->rules('max:200')->hideFromIndex(),

            Text::make('Serie')->rules('max:50')->sortable(),

            BelongsTo::make('Tipo de Gasto', 'tipoGasto', TipoGasto::class)
                ->rules('required'),

            BelongsTo::make('Tipo de Movimiento', 'tipoMovimiento', TipoMovimiento::class)
                ->rules('required')->hideFromIndex(),

            Currency::make('Monto')->sortable()->rules('required'),

            BelongsTo::make('Usuario', 'usuario', Usuario::class)
                ->hideFromIndex()
                ->rules('required'),
        ];
    }

    public function cards(Request $request): array
    {
        return [
            new GastosPerDay(),
            new RegistrosPorDia(),
            // new NuevosRegistros(),
            (new MontoRegistros())->prefix('$'),
        ];
    }

    public function filters(Request $request): array
    {
        return [
            new CuentasGastos(),
        ];
    }
}
