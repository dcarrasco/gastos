<?php

namespace App\Toa;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $fillable = ['id_ciudad', 'ciudad', 'orden'];
    protected $primaryKey = 'id_ciudad';
    public $incrementing = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_ciudades_toa');
    }

}
