<?php

namespace App\Inventario;

use App\OrmModel;

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
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 10,
            'texto_ayuda' => 'Nombre del almac&eacute;n. M&aacute;ximo 10 caracteres.',
            'es_id' => true,
            'es_obligatorio' => true,
            'es_unico' => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_almacenes');
    }

    public function __toString()
    {
        return $this->almacen;
    }
}
