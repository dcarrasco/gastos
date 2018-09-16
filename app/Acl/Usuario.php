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

    // Eloquent
    protected $fillable = [
        'nombre', 'activo', 'username', 'email',
    ];
    protected $guarded = [];
    public $timestamps = true;

    // OrmModel
    public $title = 'nombre';
    public $search = [
        'id', 'nombre', 'username', 'email'
    ];
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
                ->rules('max:45', 'required', 'unique'),

            Boolean::make('activo')
                ->sortable()
                ->rules('required'),

            Text::make('username')
                ->sortable()
                ->rules('max:30', 'required', 'unique'),

            Text::make('password')
                ->rules('max:100')
                ->hideFromIndex(),

            Text::make('email')
                ->sortable()
                ->rules('max:40'),

            Text::make('fecha login')
                ->rules('max:40')
                ->hideFromIndex(),

            Text::make('direccion ip', 'ip_login')
                ->rules('max:30')
                ->hideFromIndex(),

            Text::make('agente', 'agente_login')
                ->rules('max:200')
                ->hideFromIndex(),

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
