<?php

namespace App\Acl;

use App\Acl\Rol;
use App\Acl\UserACL;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Boolean;
use App\OrmModel\OrmField\HasMany;
use Illuminate\Notifications\Notifiable;

class Usuario extends UserACL
{
    use Notifiable;

    public $modelLabel = 'Usuario';
    public $title = 'nombre';
    public $search = ['id', 'nombre', 'username', 'email'];

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
            Id::make()->sortable(),

            Text::make('nombre')
                ->sortable()
                ->rules('max:45', 'required', 'unique')
                ->helpText('Nombre del usuario. M&aacute;ximo 45 caracteres.'),

            Boolean::make('activo')
                ->sortable()
                ->rules('required')
                ->helpText('Indica se el usuario est&aacute; activo dentro del sistema.'),

            Text::make('username')
                ->sortable()
                ->rules('max:30', 'required', 'unique')
                ->helpText('Username para el ingreso al sistema. M&aacute;ximo 30 caracteres.'),

            Text::make('password')
                ->rules('max:100')
                ->hideFromIndex()
                ->helpText('Password para el ingreso al sistema. M&aacute;ximo 40 caracteres.'),

            Text::make('email')
                ->sortable()
                ->rules('max:40')
                ->helpText('Correo del usuario. M&aacute;ximo 40 caracteres.'),

            Text::make('fecha login')
                ->rules('max:40')
                ->hideFromIndex()
                ->helpText('Fecha de la &uacute;ltima entrada al sistema.'),

            Text::make('direccion ip', 'ip_login')
                ->rules('max:30')
                ->hideFromIndex()
                ->helpText('Direcci&oacute;n IP de la &uacute;ltima entrada al sistema.'),

            Text::make('agente', 'agente_login')
                ->rules('max:200')
                ->hideFromIndex()
                ->helpText('Agente web de la &uacute;ltima entrada al sistema.'),

            HasMany::make('rol'),
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
