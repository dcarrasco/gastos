<?php

namespace App\Gastos;

use App\Gastos\Banco;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $fillable = ['banco_id', 'cuenta'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_cuentas';
    }

    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }
}
