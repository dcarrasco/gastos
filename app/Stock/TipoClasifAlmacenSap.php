<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class TipoClasifAlmacenSap extends Model
{
    protected $fillable = ['tipo', 'color'];
    protected $primaryKey = 'id_tipoclasif';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipo_clasifalm_sap');
    }
}
