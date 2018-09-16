<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;

class App extends OrmModel
{
    public $modelLabel = 'Aplicacion';
    public $title = 'app';

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
            Id::make()->sortable(),

            Text::make('aplicacion', 'app')
                ->sortable()
                ->rules('max:50', 'required', 'unique')
                ->helpText('Nombre de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:50', 'required')
                ->helpText('Breve descripcion de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.'),

            Number::make('orden')
                ->sortable()
                ->rules('required', 'unique')
                ->helpText('Orden de la aplicaci&oacute;n en el menu.'),

            Text::make('url')
                ->rules('max:100')
                ->hideFromIndex()
                ->helpText('Direcci&oacute;n web (URL) de la aplicaci&oacute;n. M&aacute;ximo 100 caracteres.'),

            Text::make('Icono')
                ->rules('max:50')
                ->helpText('Nombre del archivo del &iacute;cono de la aplicaci&oacute;n. '
                    .'M&aacute;ximo 50 caracteres.'),
        ];
    }
}
