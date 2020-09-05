<?php

namespace App\Models\Acl;

use App\Models\Acl\Rol;
use App\Models\Acl\UserACL;
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

    public function avatarLink()
    {
        return "https://secure.gravatar.com/avatar/".md5($this->email)."?size=24";
    }
}
