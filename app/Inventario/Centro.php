<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class Centro extends OrmModel
{
    // Eloquent
    protected $fillable = ['centro'];
    protected $primaryKey = 'centro';
    public $incrementing = false;

    // OrmModel
    public $title = 'centro';
    public $search = [
        'centro'
    ];
    public $modelOrder = 'centro';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_centros');
    }

    public function fields() {
        return [
            Text::make('centro')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),
        ];
    }
}
