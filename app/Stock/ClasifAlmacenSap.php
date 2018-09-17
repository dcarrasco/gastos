<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\Select;
use App\OrmModel\OrmField\HasMany;
use App\OrmModel\OrmField\BelongsTo;

class ClasifAlmacenSap extends OrmModel
{
    // Eloquent
    public $label = 'Clasificacion de Almacen SAP';
    protected $fillable = ['clasificacion', 'orden', 'dir_responsable', 'estado_ajuste', 'id_tipoclasif', 'tipo_op'];
    protected $primaryKey = 'id_clasif';
    public $timestamps = false;

    // OrmModel
    public $title = 'tipo';
    public $search = [
        'id_clasif', 'clasificacion'
    ];
    public $modelOrder = 'id_clasif';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_clasifalm_sap');
    }

    public function fields() {
        return [
            Id::make('id clasif')->sortable(),

            Text::make('clasificacion')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('orden')
                ->sortable()
                ->rules('required'),

            Select::make('dir responsable')
                ->sortable()
                ->options([
                    '*' => 'Por material',
                    'TERMINALES' => 'Terminales',
                    'REDES' => 'Redes',
                    'EMPRESAS' => 'Empresas',
                    'LOGISTICA' => 'Log&iacute;stica',
                    'TTPP' => 'Telefon&iacute;a P&uacute;blica',
                    'MARKETING' => 'Marketing',
                ])
                ->rules('required'),

            Select::make('estado ajuste')
                ->sortable()
                ->options([
                    'EXISTE' => 'Existe',
                    'NO_EXISTE' => 'No existe',
                    'NO_SABEMOS' => 'No sabemos',
                ])
                ->rules('required'),

            // BelongsTo::make('tipo clasificacion almacen', 'tipoClasifAlmacenSap'),

            Select::make('dir responsable')
                ->sortable()
                ->options([
                    'MOVIL' => 'Operaci&oacute;n M&oacute;vil',
                    'FIJA' => 'Operaci&oacute;n Fija'
                ])
                ->rules('required'),

            // HasMany::make('tipo almacen SAP', 'tipoAlmacenSap'),


        ];
    }

    public function tipoClasifAlmacenSap()
    {
        return $this->belongsTo(TipoClasifAlmacenSap::class, 'id_tipoclasif');
    }

    public function tipoAlmacenSap()
    {
        return $this->belongsToMany(
            TipoAlmacenSap::class,
            config('invfija.bd_clasif_tipoalm_sap'),
            'id_clasif',
            'id_tipo'
        );
    }
}
