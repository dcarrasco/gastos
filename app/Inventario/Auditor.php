<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Boolean;

class Auditor extends OrmModel
{
    // Eloquent
    protected $fillable = ['nombre', 'activo'];
    protected $guarded = [];

    // OrmModel
    public $title = 'nombre';
    public $search = [
        'id', 'nombre'
    ];
    public $modelOrder = ['nombre' => 'asc'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_auditores');
    }

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
}
