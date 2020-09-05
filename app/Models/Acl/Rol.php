<?php

namespace App\Models\Acl;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $fillable = ['app_id', 'rol', 'descripcion'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_rol');
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function modulo()
    {
        return $this->belongsToMany(Modulo::class, config('invfija.bd_rol_modulo'))->withTimestamps();
    }
}
