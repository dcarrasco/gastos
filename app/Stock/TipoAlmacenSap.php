<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Select;
use App\OrmModel\OrmField\Boolean;
use App\OrmModel\OrmField\HasMany;

class TipoAlmacenSap extends OrmModel
{
    // Eloquent
    public $label = 'Tipo Almacen SAP';
    protected $fillable = [
        'tipo', 'tipo_op', 'es_sumable'
    ];
    protected $primaryKey = 'id_tipo';
    public $timestamps = false;

    // OrmModel
    public $title = 'tipo';
    public $search = [
        'tipo',
    ];
    public $modelOrder = 'id_tipo';



    public function fields() {
        return [
            Id::make('id tipo')->sortable(),

            Text::make('tipo')
                ->sortable()
                ->rules('max:50', 'required'),

            Select::make('tipo operacion', 'tipo_op')
                ->sortable()
                ->options([
                    'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                    'FIJA' => 'Operaci&oacute;n Fija'
                ])
                ->rules('required'),

            Boolean::make('es sumable')
                ->rules('required'),

            HasMany::make('almacen'),
        ];
    }
    //     'almacen' => [
    //         'tipo' => OrmField::TIPO_HAS_MANY,
    //         'relationModel' => AlmacenSap::class,
    //         'relationConditions' => ['tipo_op' => '@field_value:tipo_op:MOVIL'],
    //         'textoAyuda' => 'Tipos asociados al almac&eacute;n.',
    //     ],
    // ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tiposalm_sap');
    }

    public function __toString()
    {
        return (string) $this->tipo;
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
