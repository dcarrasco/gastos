<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\BelongsTo;

class TipoGasto extends Resource
{
    public $model = 'App\Gastos\TipoGasto';
    public $label = 'Tipo de Gasto';
    public $icono = 'sitemap';
    public $title = 'tipo_gasto';
    public $search = [
        'id', 'tipo_gasto'
    ];

    public $orderBy = [
        'tipo_movimiento_id' => 'asc',
        'tipo_gasto' => 'asc'
    ];

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Tipo Movimiento', 'tipoMovimiento', TipoMovimiento::class),

            Text::make('Tipo gasto')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }

    public function getFormTipoGasto(Request $request)
    {
        $inputName = 'tipo_gasto_id';
        $tiposGasto = $this->model()
            ->orderBy('tipo_movimiento_id', 'asc')
            ->orderBy('tipo_gasto', 'asc')
            ->get();

        $options = $tiposGasto->mapWithKeys(function($tipoGasto) {
                return [$tipoGasto->tipoMovimiento->tipo_movimiento => $tipoGasto->tipo_movimiento_id];
            })
            ->map(function($tipo_movimiento_id, $tipoMov) use ($tiposGasto) {
                return $tiposGasto->filter(function($tipoGasto) use ($tipo_movimiento_id) {
                    return $tipoGasto->tipo_movimiento_id === $tipo_movimiento_id;
                })->mapWithKeys(function($tipoGasto) {
                    return [$tipoGasto->getKey() => $tipoGasto->tipo_gasto];
                })->all();
            })
            ->all();

        return \Form::select($inputName, $options, $request->input($inputName), ['class' => 'form-control form-control-sm']);
    }

}
