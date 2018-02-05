<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Almacen extends OrmModel
{
    public $modelLabel = 'Almacen';

    protected $fillable = ['almacen'];

    protected $guarded = [];

    protected $primaryKey = 'almacen';

    public $incrementing = false;

    public $modelFields = [
        'almacen' => [
            'label' => 'Almac&eacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'Nombre del almac&eacute;n. M&aacute;ximo 10 caracteres.',
            'es_id' => true,
            'esObligatorio' => true,
            'esUnico' => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_almacenes');
    }

    public function __toString()
    {
        return (string) $this->almacen;
    }
}
