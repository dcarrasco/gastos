<?php

namespace App;

use App\Acl\Rol;
use App\Acl\UserACL;
use App\OrmModel\OrmField\IdField;
use App\OrmModel\OrmField\CharField;
use App\OrmModel\OrmField\BooleanField;
use App\OrmModel\OrmField\HasManyField;
use Illuminate\Notifications\Notifiable;

class Usuario extends UserACL
{
    use Notifiable;

    public $modelLabel = 'Usuario';
    public $title = 'nombre';

    public $timestamps = true;
    protected $fillable = [
        'nombre', 'activo', 'username', 'email',
    ];

    protected $guarded = [];

    public $modelOrder = 'nombre';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_usuarios');
    }

    public function fields()
    {
        return [
            IdField::make()->sortable(),

            CharField::make('nombre')
                ->sortable()
                ->rules('max:45', 'required', 'unique')
                ->helpText('Nombre del usuario. M&aacute;ximo 45 caracteres.'),

            BooleanField::make('activo')
                ->sortable()
                ->rules('required')
                ->helpText('Indica se el usuario est&aacute; activo dentro del sistema.'),

            CharField::make('username')
                ->sortable()
                ->rules('max:30', 'required', 'unique')
                ->helpText('Username para el ingreso al sistema. M&aacute;ximo 30 caracteres.'),

            CharField::make('password')
                ->rules('max:100')
                ->hideFromIndex()
                ->helpText('Password para el ingreso al sistema. M&aacute;ximo 40 caracteres.'),

            CharField::make('email')
                ->sortable()
                ->rules('max:40')
                ->helpText('Correo del usuario. M&aacute;ximo 40 caracteres.'),

            CharField::make('fecha login')
                ->rules('max:40')
                ->hideFromIndex()
                ->helpText('Fecha de la &uacute;ltima entrada al sistema.'),

            CharField::make('direccion ip', 'ip_login')
                ->rules('max:30')
                ->hideFromIndex()
                ->helpText('Direcci&oacute;n IP de la &uacute;ltima entrada al sistema.'),

            CharField::make('agente', 'agente_login')
                ->rules('max:200')
                ->hideFromIndex()
                ->helpText('Agente web de la &uacute;ltima entrada al sistema.'),

            HasManyField::make(Rol::class),
        ];
    }

    public function rol()
    {
        return $this->belongsToMany(Rol::class, config('invfija.bd_usuario_rol'))->withTimestamps();
    }

    public function getFirstName()
    {
        return head(explode(' ', $this->nombre));
    }
}
