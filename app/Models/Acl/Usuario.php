<?php

namespace App\Models\Acl;

use App\Models\Acl\Rol;
use App\Models\Acl\UserACL;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends UserACL
{
    use Notifiable;
    use HasFactory;

    protected $fillable = [
        'nombre', 'activo', 'username', 'email',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'acl_usuarios';
    }

    public function rol()
    {
        return $this->belongsToMany(Rol::class, 'acl_usuario_rol')->withTimestamps();
    }

    public function getFirstName(): string
    {
        return head(explode(' ', $this->nombre));
    }

    public function avatarLink(): string
    {
        return 'https://secure.gravatar.com/avatar/' . md5($this->email) . '?size=24';
    }
}
