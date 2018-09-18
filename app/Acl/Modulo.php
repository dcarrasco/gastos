<?php

namespace App\Acl;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $fillable = ['id_app', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_modulos');
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
