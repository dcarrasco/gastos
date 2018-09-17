<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Select;

class AlmacenSap extends OrmModel
{
    // Eloquent
    public $label = 'Almacen SAP';
    protected $fillable = ['centro', 'cod_almacen', 'des_almacen', 'uso_almacen', 'responsable', 'tipo_op'];
    protected $primaryKey = 'id_clasif';
    public $incrementing = false;
    public $timestamps = false;

    // OrmModel
    public $title = 'des_almacen';
    public $search = [
        'id_clasif', 'clasificacion'
    ];
    public $modelOrder = ['centro' => 'asc', 'cod_almacen' => 'asc'];



    public function fields() {
        return [
            Text::make('centro')
                ->sortable()
                ->rules('max:10', 'required'),

            Text::make('cod almacen')
                ->sortable()
                ->rules('max:10', 'required'),

            Text::make('descripcion', 'des_almacen')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('uso almacen')
                ->sortable()
                // ->hideFromIndex()
                ->rules('max:50', 'required'),

            Text::make('responsable')
                ->sortable()
                // ->hideFromIndex()
                ->rules('max:50', 'required'),

            Text::make('responsable')
                ->sortable()
                ->rules('max:50', 'required'),

            Select::make('tipo operacion', 'tipo_op')
                ->sortable()
                // ->hideFromIndex()
                ->options([
                    'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                    'FIJA' => 'Operaci&oacute;n Fija'
                ])
                ->rules('required'),
        ];
    }

    //     'tipos' => [
    //         'tipo' => OrmField::TIPO_HAS_MANY,
    //         'relationModel' => TipoAlmacenSap::class,
    //         'relationConditions' => ['tipo_op' => '@field_value:tipo_op:MOVIL'],
    //         'textoAyuda' => 'Tipos asociados al almac&eacuten.',
    //     ],
    // ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_almacenes_sap');
    }

    public function __toString()
    {
        return (string) $this->centro.'-'.$this->cod_almacen.' '.$this->des_almacen;
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
