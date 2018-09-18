<?php

namespace App\Stock;

use Illuminate\Database\Eloquent\Model;

class UsuarioSap extends Model
{
    protected $fillable = ['usuario', 'nom_usuario'];
    protected $primaryKey = 'usuario';
    public $incrementing = false;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_usuarios_sap');
    }
}
