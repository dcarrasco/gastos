<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Acl\UserACL;
use App\Acl\Rol;

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
            'tipo'   => OrmModel::TIPO_ID,
        ],
        'nombre' => [
            'label'          => 'Nombre de usuario',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 45,
            'texto_ayuda'    => 'Nombre del usuario. M&aacute;ximo 45 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'activo' => [
            'label'          => 'Activo',
            'tipo'           => OrmModel::TIPO_BOOLEAN,
            'texto_ayuda'    => 'Indica se el usuario est&aacute; activo dentro del sistema.',
            'es_obligatorio' => true,
        ],
        'username' => [
            'label'          => 'Username',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 30,
            'texto_ayuda'    => 'Username para el ingreso al sistema. M&aacute;ximo 30 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'password' => [
            'label'          => 'Password',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 100,
            'texto_ayuda'    => 'Password para el ingreso al sistema. M&aacute;ximo 40 caracteres.',
            'mostrar_lista'  => false,
        ],
        'email' => [
            'label'          => 'Correo',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 40,
            'texto_ayuda'    => 'Correo del usuario. M&aacute;ximo 40 caracteres.',
            'mostrar_lista'  => false,
        ],
        'fecha_login' => [
            'label'          => 'Fecha &uacute;ltimo login',
            'tipo'           => OrmModel::TIPO_DATETIME,
            'largo'          => 40,
            'texto_ayuda'    => 'Fecha de la &uacute;ltima entrada al sistema.',
            'mostrar_lista'  => false,
        ],
        'ip_login' => [
            'label'          => 'Direcci&oacute;n IP',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 30,
            'texto_ayuda'    => 'Direcci&oacute;n IP de la &uacute;ltima entrada al sistema.',
            'mostrar_lista'  => false,
        ],
        'agente_login' => [
            'label'          => 'Agente',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 200,
            'texto_ayuda'    => 'Agente web de la &uacute;ltima entrada al sistema.',
            'mostrar_lista'  => false,
        ],
        'rol' => [
            'tipo'           => OrmModel::TIPO_HAS_MANY,
            'relation_model' => Rol::class,
            'texto_ayuda'    => 'Roles asociados al usuario.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_usuarios');
    }

    public function __toString()
    {
        return $this->nombre;
    }

    public function rol()
    {
        return $this->belongsToMany(Rol::class, config('invfija.bd_usuario_rol'), 'id_usuario', 'id_rol');
    }

    public function getFirstName()
    {
        return head(explode(' ', $this->nombre));
    }
}
