<?php

namespace App\Inventario;

use App\OrmModel;

class TipoUbicacion extends OrmModel
{
    public $modelLabel = 'Tipo de Ubicacion';

    public $modelOrder = 'tipo_inventario';

    protected $fillable = ['tipo_inventario', 'tipo_ubicacion'];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo' => OrmModel::TIPO_ID,
        ],
        'tipo_inventario' => [
            'tipo' => OrmModel::TIPO_HAS_ONE,
            'relation_model' => TipoInventario::class,
            'texto_ayuda' => 'Seleccione el tipo de inventario.',
            'es_obligatorio' => true,
        ],
        'tipo_ubicacion' => [
            'label' => 'Tipo de ubicaci&oacute;n',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 30,
            'texto_ayuda' => 'M&aacute;ximo 30 caracteres.',
            'es_obligatorio' => true,
            'es_unico' => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipo_ubicacion');
    }

    public function __toString()
    {
        return $this->tipo_ubicacion;
    }

    public function tipoInventario()
    {
        return $this->belongsTo(TipoInventario::class, 'tipo_inventario');
    }
}
