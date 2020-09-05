<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $fillable = ['app_id', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];

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
