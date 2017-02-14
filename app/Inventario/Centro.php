<?php

namespace App\Inventario;

use App\OrmModel;

class Centro extends OrmModel
{
    public $modelLabel = 'Centro';

    protected $fillable = ['centro'];

    protected $guarded = [];

    protected $primaryKey = 'centro';
    public $incrementing = false;

    public $modelFields = [
        'centro' => [
            'label'          => 'Centro',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'Nombre del centro. M&aacute;ximo 10 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_centros');
    }

    public function __toString()
    {
        return $this->centro;
    }
}
