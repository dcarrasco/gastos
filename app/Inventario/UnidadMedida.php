<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class UnidadMedida extends OrmModel
{
    public $modelLabel = 'Unidad de medida';

    public $timestamps = true;
    protected $fillable = ['unidad', 'desc_unidad'];

    protected $guarded = [];

    protected $primaryKey = 'unidad';

    public $incrementing = false;

    public $modelFields = [
        'unidad' => [
            'label' => 'Unidad',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'Unidad de medida. M&aacute;ximo 10 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'desc_unidad' => [
            'label' => 'Descripci&oacute;n unidad de medida',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Descripci&oacute;n de la unidad de medida. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_unidades');
    }

    public function __toString()
    {
        return (string) $this->desc_unidad;
    }
}
