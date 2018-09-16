<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class Almacen extends OrmModel
{
    // Eloquent
    protected $fillable = ['almacen'];
    protected $primaryKey = 'almacen';
    public $incrementing = false;

    // OrmModel
    public $title = 'almacen';
    public $search = [
        'centro'
    ];
    public $modelOrder = 'almacen';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_almacenes');
    }

    public function fields() {
        return [
            Text::make('almacen')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),
        ];
    }
}
