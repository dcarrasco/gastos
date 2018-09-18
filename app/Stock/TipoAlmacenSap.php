<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class TipoAlmacenSap extends Model
{
    public $label = 'Tipo Almacen SAP';
    protected $fillable = [
        'tipo', 'tipo_op', 'es_sumable'
    ];
    protected $primaryKey = 'id_tipo';
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tiposalm_sap');
    }

    public function getAlmacenAttribute()
    {
        return $this->belongsToMany(
            AlmacenSap::class,
            config('invfija.bd_tipoalmacen_sap'),
            'id_tipo',
            'centro'
        );
    }

    public static function getComboTiposOperacion($tipoOp = 'movil')
    {
        return models_array_options(
            self::where('tipo_op', $tipoOp)
                ->orderBy('tipo')
                ->get()
        );
    }
}
