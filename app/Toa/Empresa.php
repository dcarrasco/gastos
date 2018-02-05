<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;
use App\Stock\TipoAlmacenSap;

class Empresa extends OrmModel
{
    public $modelLabel = 'Empresa TOA';

    public static $orderField = 'empresa';

    protected $fillable = ['id_empresa', 'empresa'];

    protected $guarded = [];

    protected $primaryKey = 'id_empresa';

    public $incrementing = false;

    public $modelFields = [
        'id_empresa' => [
            'label' => 'ID Empresa',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 20,
            'textoAyuda' => 'ID de la empresa. M&aacute;ximo 20 caracteres.',
            'es_id' => true,
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'empresa' => [
            'label' => 'Nombre de la empresa',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre de la empresa. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'tipoAlmacenSap' => [
            'tipo' => OrmField::TIPO_HAS_MANY,
            'relationModel' => TipoAlmacenSap::class,
            'conditions' => ['id_app' => '@field_value:id_app'],
            'textoAyuda' => 'Tipos de almacen asociados a empresa TOA.',
        ],
        'ciudadToa' => [
            'tipo' => OrmField::TIPO_HAS_MANY,
            'relationModel' => CiudadToa::class,
            'conditions' => ['id_app' => '@field_value:id_app'],
            'textoAyuda' => 'Ciudades asociados a empresa TOA.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_empresas_toa');
    }

    public function __toString()
    {
        return (string) $this->empresa;
    }

    public function tipoAlmacenSap()
    {
        return $this->belongsToMany(
            TipoAlmacenSap::class,
            config('invfija.bd_empresas_toa_tiposalm'),
            'id_empresa',
            'id_tipo'
        );
    }

    public function ciudadToa()
    {
        return $this->belongsToMany(
            CiudadToa::class,
            config('invfija.bd_empresas_ciudades_toa'),
            'id_empresa',
            'id_ciudad'
        );
    }
}
