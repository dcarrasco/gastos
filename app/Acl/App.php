<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\IdField;
use App\OrmModel\OrmField\CharField;
use App\OrmModel\OrmField\NumberField;

class App extends OrmModel
{
    public $modelLabel = 'Aplicacion';

    protected $fillable = ['app', 'descripcion', 'orden', 'url', 'icono'];

    protected $guarded = [];

    public $modelOrder = 'app';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_app');
    }

    public function fields() {
        return [
            IdField::make()->sortable(),

            CharField::make('aplicacion', 'app')
                ->sortable()
                ->rules('max:50', 'required', 'unique')
                ->helpText('Nombre de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.'),

            CharField::make('descripcion')
                ->sortable()
                ->rules('max:50', 'required')
                ->helpText('Breve descripcion de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.'),

            NumberField::make('orden')
                ->sortable()
                ->rules('required', 'unique')
                ->helpText('Orden de la aplicaci&oacute;n en el menu.'),

            CharField::make('url')
                ->rules('max:100')
                ->hideFromIndex()
                ->helpText('Direcci&oacute;n web (URL) de la aplicaci&oacute;n. M&aacute;ximo 100 caracteres.'),

            CharField::make('Icono')
                ->rules('max:50')
                ->helpText('Nombre del archivo del &iacute;cono de la aplicaci&oacute;n. '
                    .'M&aacute;ximo 50 caracteres.'),
        ];
    }

    public function __toString()
    {
        return (string) $this->app;
    }
}
