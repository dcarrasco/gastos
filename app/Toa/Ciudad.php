<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;

class Ciudad extends OrmModel
{
    // Eloquent
    protected $fillable = ['id_ciudad', 'ciudad', 'orden'];
    protected $primaryKey = 'id_ciudad';
    public $incrementing = false;

    // OrmModel
    public $title = 'ciudad';
    public $search = [
        'id_ciudad', 'ciudad',
    ];
    public $modelOrder = 'orden';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_ciudades_toa');
    }

    public function fields()
    {
        return [
            Text::make('id', 'id_ciudad')
                ->sortable()
                ->rules('max:5', 'required', 'unique'),

            Text::make('ciudad')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('orden')
                ->sortable()
                ->rules('required'),

        ];
    }
}
