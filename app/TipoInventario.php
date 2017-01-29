<?php

namespace App;

class TipoInventario extends OrmModel
{
    public $modelLabel = 'Tipo de inventario';

    protected $fillable = [
        'almacen',
    ];

    protected $guarded = [];

    protected $primaryKey = 'id_tipo_inventario';
    public $incrementing = false;

    public $modelFields = [
        'id_tipo_inventario' => [
            'label'          => 'Tipo de inventario',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 10,
            'texto_ayuda'    => 'M&aacute;ximo 10 caracteres.',
            'es_id'          => true,
            'es_obligatorio' => true,
            'es_unico'       => true,
        ],
        'desc_tipo_inventario' => [
            'label'          => 'Descripci&oacute;n tipo de inventario',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Descripci&oacute;n del tipo de inventario. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipos_inventario');
    }

    public function __toString()
    {
        return $this->desc_tipo_inventario;
    }
}
