<?php

namespace App\Inventario;

use Illuminate\Database\Eloquent\Model;

class Catalogo extends Model
{
    protected $fillable = ['catalogo', 'descripcion', 'pmp', 'es_seriado'];
    protected $primaryKey = 'catalogo';
    public $incrementing = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_catalogos');
    }
}
