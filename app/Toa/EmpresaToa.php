<?php

namespace App\Toa;

use App\OrmModel;
use App\Stock\TipoAlmacenSap;

class EmpresaToa extends OrmModel
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
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 20,
            'texto_ayuda' => 'ID de la empresa. M&aacute;ximo 20 caracteres.',
            'es_id' => true,
            'es_obligatorio' => true,
            'es_unico' => true
        ],
        'empresa' => [
            'label' => 'Nombre de la empresa',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 50,
            'texto_ayuda' => 'Nombre de la empresa. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico' => true
        ],
        'tipoAlmacenSap' => [
            'tipo' => OrmModel::TIPO_HAS_MANY,
            'relation_model' => TipoAlmacenSap::class,
            'conditions' => ['id_app' => '@field_value:id_app'],
            'texto_ayuda' => 'Tipos de almacen asociados a empresa TOA.',
        ],
        'ciudadToa' => [
            'tipo' => OrmModel::TIPO_HAS_MANY,
            'relation_model' => CiudadToa::class,
            'conditions' => ['id_app' => '@field_value:id_app'],
            'texto_ayuda' => 'Ciudades asociados a empresa TOA.',
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
