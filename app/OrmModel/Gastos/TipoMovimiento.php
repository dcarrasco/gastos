<?php

namespace App\OrmModel\Gastos;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Select;

class TipoMovimiento extends Resource
{
    public $model = 'App\Gastos\TipoMovimiento';
    public $label = 'Tipo de Movimiento';
    public $labelPlural = 'Tipos de Movimiento';
    public $icono = 'sitemap';
    public $title = 'tipo_movimiento';
    public $search = [
        'id', 'tipo_movimiento'
    ];

    public $orderBy = 'tipo_movimiento';

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            Text::make('Tipo movimiento')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Select::make('Signo')->options([
                '1' => 'Positivo',
                '-1' => 'Negativo',
            ]),
        ];
    }


    public function getFormTipoMovimiento(Request $request)
    {
        $inputName = 'tipo_movimiento_id';
        $options = $this->resourceOrderBy($request)
            ->model()->get()
            ->mapWithKeys(function($tipoMovimiento) {
                return [$tipoMovimiento->getKey() => $tipoMovimiento->tipo_movimiento];
            })
            ->all();

        return \Form::select($inputName, $options, $request->input($inputName), ['class' => 'form-control']);
    }
}
