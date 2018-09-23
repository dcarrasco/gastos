<?php

namespace App\Gastos;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $fillable = ['nombre'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_bancos';
    }
}
