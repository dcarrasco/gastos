<?php

namespace App\OrmModel\Stock;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;

class TipoClasifAlmacenSap extends Resource
{
    public $model = 'App\Stock\TipoClasifAlmacenSap';
    public $icono = 'th';
    public $label = 'Tipo Clasificacion de Almacen SAP';
    public $title = 'tipo';
    public $search = [
        'id_tipoclasif', 'tipo', 'color'
    ];
    public $orderBy = 'id_tipoclasif';

    public function fields(Request $request)
    {
        return [
            Id::make('id', 'id_tipoclasif')->sortable(),

            Text::make('tipo')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('color')
                ->sortable()
                ->rules('max:50'),
        ];
    }
}
