<?php

namespace App\Acl;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    protected $fillable = ['app', 'descripcion', 'orden', 'url', 'icono'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_app');
    }
}
