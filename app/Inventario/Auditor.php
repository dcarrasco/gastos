<?php

namespace App\Inventario;

use Illuminate\Database\Eloquent\Model;

class Auditor extends Model
{
    protected $fillable = ['nombre', 'activo'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_auditores');
    }
}
