<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Select;

class Familia extends OrmModel
{
    // Eloquent
    protected $fillable = ['codigo', 'tipo', 'nombre'];
    protected $guarded = [];
    protected $primaryKey = 'codigo';
    public $incrementing = false;

    // OrmModel
    public $title = 'nombre';
    public $search = [
        'codigo', 'nombre'
    ];
    public $modelOrder = ['codigo' => 'asc'];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_familias');
    }

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
}
