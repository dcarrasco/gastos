<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Usuario extends UserACL
{
    use Notifiable;
    use HasFactory;

    protected $table = 'acl_usuarios';

    protected $fillable = [
        'nombre', 'activo', 'username', 'email',
    ];
}
