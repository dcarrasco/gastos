<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class TipoInventario extends OrmModel
{
    public $modelLabel = 'Tipo de inventario';

    protected $fillable = ['id_tipo_inventario', 'desc_tipo_inventario'];

    protected $guarded = [];

    public $timestamps = true;
    protected $primaryKey = 'id_tipo_inventario';

    public $incrementing = false;

    public $modelFields = [
        'id_tipo_inventario' => [
            'label' => 'Tipo de inventario',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'M&aacute;ximo 10 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true,
        ],
        'desc_tipo_inventario' => [
            'label' => 'Descripci&oacute;n tipo de inventario',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Descripci&oacute;n del tipo de inventario. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipos_inventario');
    }

    public function __toString()
    {
        return (string) $this->desc_tipo_inventario;
    }
}
