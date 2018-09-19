<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\Filters\PerPage;
use App\OrmModel\OrmField\Boolean;
use App\OrmModel\Filters\AuditoresActivos;

class Auditor extends Resource
{
    public $model = 'App\Inventario\Auditor';
    public $icono = 'list';
    public $title = 'nombre';
    public $search = [
        'id', 'nombre'
    ];
    public $order = ['nombre' => 'asc'];

    public function fields()
    {
        return [
            Id::make()->sortable(),

            Text::make('nombre')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Boolean::make('activo')
                ->sortable()
                ->rules('required'),
        ];
    }

    public function filters(Request $request)
    {
        return [
            new AuditoresActivos,
        ];
    }
}
