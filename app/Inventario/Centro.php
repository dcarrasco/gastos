<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Centro extends OrmModel
{
    public $modelLabel = 'Centro';

    protected $fillable = ['centro'];

    protected $guarded = [];

    protected $primaryKey = 'centro';
    public $incrementing = false;

    public $modelFields = [
        'centro' => [
            'label' => 'Centro',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'Nombre del centro. M&aacute;ximo 10 caracteres.',
            'es_id' => true,
            'esObligatorio' => true,
            'esUnico' => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_centros');
    }

    public function __toString()
    {
        return (string) $this->centro;
    }
}
