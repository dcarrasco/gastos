<?php

namespace App\Models\Acl;

use App\Models\Acl\UserACL;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends UserACL
{
    use Notifiable;
    use HasFactory;

    protected $table = 'acl_usuarios';

    protected $fillable = [
        'nombre', 'activo', 'username', 'email',
    ];
}
