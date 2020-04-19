<?php

namespace App\Acl;

use App\Acl\Rol;
use App\Acl\UserACL;
use Illuminate\Notifications\Notifiable;

class Usuario extends UserACL
{
    use Notifiable;

    protected $fillable = [
        'nombre', 'activo', 'username', 'email',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_usuarios');
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
