<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Select;
use App\OrmModel\Filters\FamiliaSubFamilia;

class Familia extends Resource
{
    public $model = 'App\Inventario\Familia';
    public $icono = 'th';
    public $title = 'nombre';
    public $search = [
        'codigo', 'nombre'
    ];
    public $order = ['codigo' => 'asc'];

    public function fields()
    {
        return [
            Text::make('codigo')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Select::make('tipo')
                ->sortable()
                ->options([
                    'FAM' => 'Familia',
                    'SUBFAM' => 'SubFamilia'
                ])
                ->rules('max:30', 'required'),

            Text::make('nombre')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }

    public function filters(Request $request) {
        return [
            new FamiliaSubFamilia,
        ];
    }
}
