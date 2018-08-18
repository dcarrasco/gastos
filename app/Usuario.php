<?php

namespace App;

use App\Acl\Rol;
use App\OrmModel\OrmField;
use App\Acl\UserACL;
use Illuminate\Notifications\Notifiable;

class Usuario extends UserACL
{
    use Notifiable;

    public $modelLabel = 'Usuario';

    protected $fillable = [
        'nombre', 'activo', 'username', 'email',
    ];

    protected $guarded = [];

    protected $modelFields = [
        'id' => [
            'tipo'   => OrmField::TIPO_ID,
        ],
        'nombre' => [
            'label'          => 'Nombre de usuario',
            'tipo'           => OrmField::TIPO_CHAR,
            'largo'          => 45,
            'textoAyuda'    => 'Nombre del usuario. M&aacute;ximo 45 caracteres.',
            'esObligatorio' => true,
            'esUnico'       => true
        ],
        'activo' => [
            'label'          => 'Activo',
            'tipo'           => OrmField::TIPO_BOOLEAN,
            'textoAyuda'    => 'Indica se el usuario est&aacute; activo dentro del sistema.',
            'esObligatorio' => true,
        ],
        'username' => [
            'label'          => 'Username',
            'tipo'           => OrmField::TIPO_CHAR,
            'largo'          => 30,
            'textoAyuda'    => 'Username para el ingreso al sistema. M&aacute;ximo 30 caracteres.',
            'esObligatorio' => true,
            'esUnico'       => true
        ],
        'password' => [
            'label'          => 'Password',
            'tipo'           => OrmField::TIPO_CHAR,
            'largo'          => 100,
            'textoAyuda'    => 'Password para el ingreso al sistema. M&aacute;ximo 40 caracteres.',
            'mostrarLista'  => false,
        ],
        'email' => [
            'label'          => 'Correo',
            'tipo'           => OrmField::TIPO_CHAR,
            'largo'          => 40,
            'textoAyuda'    => 'Correo del usuario. M&aacute;ximo 40 caracteres.',
            'mostrarLista'  => false,
        ],
        'fecha_login' => [
            'label'          => 'Fecha &uacute;ltimo login',
            'tipo'           => OrmField::TIPO_DATETIME,
            'largo'          => 40,
            'textoAyuda'    => 'Fecha de la &uacute;ltima entrada al sistema.',
            'mostrarLista'  => false,
        ],
        'ip_login' => [
            'label'          => 'Direcci&oacute;n IP',
            'tipo'           => OrmField::TIPO_CHAR,
            'largo'          => 30,
            'textoAyuda'    => 'Direcci&oacute;n IP de la &uacute;ltima entrada al sistema.',
            'mostrarLista'  => false,
        ],
        'agente_login' => [
            'label'          => 'Agente',
            'tipo'           => OrmField::TIPO_CHAR,
            'largo'          => 200,
            'textoAyuda'    => 'Agente web de la &uacute;ltima entrada al sistema.',
            'mostrarLista'  => false,
        ],
        'rol' => [
            'tipo'           => OrmField::TIPO_HAS_MANY,
            'relationModel' => Rol::class,
            'textoAyuda'    => 'Roles asociados al usuario.',
        ],
    ];

    public $modelOrder = 'nombre';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_usuarios');
    }

    public function __toString()
    {
        return (string) $this->nombre;
    }

    public function rol()
    {
        return $this->belongsToMany(Rol::class, config('invfija.bd_usuario_rol'));
    }

    public function getFirstName()
    {
        return head(explode(' ', $this->nombre));
    }
}
