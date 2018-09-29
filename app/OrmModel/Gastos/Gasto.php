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

            Text::make('Fecha')->sortable()->hideFromIndex(),

            Text::make('Glosa')->sortable()->hideFromIndex(),

            Text::make('Serie')->sortable()->hideFromIndex(),

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

    public function procesaMasivo(Request $request)
    {
        if (! $request->has('datos')) {
            return [];
        }

        return collect(explode(PHP_EOL, $request->input('datos')))
            ->map(function($linea) use ($request) {
                return $this->procesaLineaMasivo($request, $linea);
            })
            ->filter(function($gasto) {
                return ! is_null($gasto);
            })
            ->filter(function ($gasto) use ($request) {
                $gastoAnterior = (new $this->model)->where([
                    'cuenta_id' => $request->cuenta_id,
                    'anno' => $request->anno,
                    'mes' => $request->mes,
                    'fecha' => $gasto->fecha,
                    'serie' => $gasto->serie,
                ])
                ->get()
                ->first();

                return is_null($gastoAnterior);
            })
            ->all();
    }

    public function procesaLineaMasivo(Request $request, $linea = '')
    {
        if (empty($linea)) {
            return null;
        }

        $linea = explode(' ', $linea);

        foreach($linea as $item => $dato) {
            if (preg_match('/^[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9]/', $dato) === 1) {
                $item_fecha = $item;
                $fecha = $linea[$item_fecha];
                $fecha = (new Carbon)->create(2000 + (int)substr($fecha, 6, 2), substr($fecha, 3, 2), substr($fecha, 0, 2), 0, 0, 0);
            }
        }

        $glosa = collect($linea)->only(range($item_fecha + 2, count($linea) - 8))->implode(' ');
        $tipoGasto = (new TipoGastoModel)->findOrNew((new GlosaTipoGasto)->getPorGlosa($request->cuenta_id, $glosa));

        return (new $this->model)->fill([
            'cuenta_id' => $request->cuenta_id,
            'anno' => $request->anno,
            'mes' => $request->mes,
            'fecha' => $fecha,
            'serie' => $linea[$item_fecha + 1],
            'glosa' => $glosa,
            'tipo_gasto_id' => $tipoGasto->id,
            'tipo_movimiento_id' => isset($tipoGasto->tipoMovimiento) ? $tipoGasto->tipoMovimiento->id : null,
            'monto' => (int) str_replace('.', '', $linea[count($linea)-1]),
            'usuario_id' => auth()->id(),
        ]);
    }
}
