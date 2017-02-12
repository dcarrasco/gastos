<?php

namespace App\Inventario;

use App\OrmModel;

class UnidadMedida extends OrmModel
{
    public $modelLabel = 'Unidad de medida';

    protected $fillable = [
        'unidad', 'desc_unidad',
    ];

    protected $guarded = [];

    protected $primaryKey = 'unidad';
    public $incrementing = false;

    public $modelFields = [
        'unidad' => [
            'label'          => 'Unidad',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'Unidad de medida. M&aacute;ximo 10 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'desc_unidad' => [
            'label'          => 'Descripci&oacute;n unidad de medida',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Descripci&oacute;n de la unidad de medida. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_unidades');
    }

    public function __toString()
    {
        return $this->desc_unidad;
    }
}
