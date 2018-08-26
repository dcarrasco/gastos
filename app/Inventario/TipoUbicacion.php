<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class TipoUbicacion extends OrmModel
{
    public $modelLabel = 'Tipo de Ubicacion';

    public $modelOrder = 'tipo_inventario';

    public $timestamps = true;
    protected $fillable = ['tipo_inventario', 'tipo_ubicacion'];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo' => OrmField::TIPO_ID,
        ],
        'tipo_inventario' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => TipoInventario::class,
            'textoAyuda' => 'Seleccione el tipo de inventario.',
            'esObligatorio' => true,
        ],
        'tipo_ubicacion' => [
            'label' => 'Tipo de ubicaci&oacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 30,
            'textoAyuda' => 'M&aacute;ximo 30 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipo_ubicacion');
    }

    public function __toString()
    {
        return (string) $this->tipo_ubicacion;
    }

    public function tipoInventario()
    {
        return $this->belongsTo(TipoInventario::class, 'tipo_inventario');
    }
}
