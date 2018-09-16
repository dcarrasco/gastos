<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\Boolean;

class Catalogo extends OrmModel
{

    // Eloquent
    protected $fillable = ['catalogo', 'descripcion', 'pmp', 'es_seriado'];
    protected $primaryKey = 'catalogo';
    public $incrementing = false;

    // OrmModel
    public $title = 'descripcion';
    public $search = [
        'catalogo', 'descripcion'
    ];
    public $modelOrder = ['catalogo' => 'asc'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_catalogos');
    }

    public function fields()
    {
        return [
            Text::make('catalogo')
                ->sortable()
                ->rules('max:20', 'required', 'unique'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('pmp')
                ->sortable()
                ->rules('required'),

            Boolean::make('es seriado')
                ->rules('required'),
        ];
    }
}
