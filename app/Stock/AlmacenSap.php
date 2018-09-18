<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class AlmacenSap extends Model
{
    protected $fillable = ['centro', 'cod_almacen', 'des_almacen', 'uso_almacen', 'responsable', 'tipo_op'];
    protected $primaryKey = 'id_clasif';
    public $incrementing = false;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_almacenes_sap');
    }

    public function tipos()
    {
        return $this->belongsToManyMultiKey(
            TipoAlmacenSap::class,
            config('invfija.bd_tipoalmacen_sap'),
            ['centro', 'cod_almacen'],
            'id_tipo'
        );
    }

    public static function getComboTiposOperacion($tipoOp = 'movil')
    {
        return models_array_options(
            self::where('tipo_op', $tipoOp)
                ->orderBy('centro')
                ->orderBy('cod_almacen')
                ->get()
        );
    }
}
